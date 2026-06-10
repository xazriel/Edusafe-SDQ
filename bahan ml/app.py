from flask import Flask, request, jsonify
from sklearn.naive_bayes import GaussianNB
from sklearn.model_selection import train_test_split, cross_val_score
from sklearn.metrics import accuracy_score, classification_report
import numpy as np
import pyreadstat
import os

app = Flask(__name__)

# ─────────────────────────────────────────────
#  Global model state
# ─────────────────────────────────────────────
nb_model    = None
nb_akurasi  = 0.0
nb_cv_score = 0.0

# ─────────────────────────────────────────────
#  Load & Train dari dataset Vietnam SDQ
# ─────────────────────────────────────────────
sav_path = os.path.join(os.path.dirname(__file__), 'V_EL-_data_SDQ_23-25.sav')

try:
    df, meta = pyreadstat.read_sav(sav_path)

    FEATURES = [
        'emotional_problems',
        'conduct_problems',
        'ADHD',
        'peer_relationship_problems',
        'prosocial_behaviour',
    ]
    LABEL = 'muc_do_SDQ'   # 1=Normal, 2=Borderline, 3=High Risk

    # Drop baris dengan nilai kosong pada kolom yang dibutuhkan
    df = df[FEATURES + [LABEL]].dropna()

    X = df[FEATURES].values
    y = df[LABEL].astype(int).values   # 1 / 2 / 3

    print("\nDistribusi Label (muc_do_SDQ):")
    for k, v in zip(*np.unique(y, return_counts=True)):
        label_name = {1: 'Normal', 2: 'Borderline', 3: 'High Risk'}[k]
        print(f"   {k} ({label_name}): {v} ({v/len(y)*100:.2f}%)")

    print("\nStatistik deskriptif per fitur:")
    for col in FEATURES:
        print(f"   {col}: mean={df[col].mean():.2f}, std={df[col].std():.2f}")

    # ── Train / Test split ──────────────────────────────────────────
    X_train, X_test, y_train, y_test = train_test_split(
        X, y, test_size=0.2, random_state=42, stratify=y
    )

    nb = GaussianNB()
    nb.fit(X_train, y_train)

    # Akurasi pada test set
    nb_akurasi = round(accuracy_score(y_test, nb.predict(X_test)) * 100, 2)

    # 5-Fold Cross Validation
    cv_scores   = cross_val_score(nb, X, y, cv=5, scoring='accuracy')
    nb_cv_score = round(cv_scores.mean() * 100, 2)
    nb_cv_std   = round(cv_scores.std() * 100, 2)

    nb_model = nb

    print(f"\nGaussian Naive Bayes siap")
    print(f"   Akurasi Test Set : {nb_akurasi}%")
    print(f"   CV 5-Fold        : {nb_cv_score}% (+/-{nb_cv_std}%)")
    print(f"\nClassification Report:")
    print(classification_report(y_test, nb.predict(X_test),
                                target_names=['Normal', 'Borderline', 'High Risk']))

    print("\nParameter model (mean per kelas per fitur):")
    for i, label in enumerate(['Normal', 'Borderline', 'High Risk']):
        means = dict(zip(FEATURES, nb.theta_[i]))
        print(f"   {label}: {means}")

except Exception as e:
    print(f"Model gagal dimuat: {e}")
    import traceback
    traceback.print_exc()


# ─────────────────────────────────────────────
#  Helper: mapping label angka → teks
# ─────────────────────────────────────────────
LABEL_MAP = {
    1: 'Normal',
    2: 'Borderline',
    3: 'High Risk',
}

def get_action(sdq_label: str, nb_label: str) -> dict:
    """
    Logika OR: jika salah satu menunjukkan risiko → perlu perhatian.
    Kembalikan keputusan_akhir + tindakan.
    """
    risk_order = {'Normal': 0, 'Borderline': 1, 'High Risk': 2}
    worse = max(sdq_label, nb_label, key=lambda x: risk_order.get(x, 0))

    if worse == 'Normal':
        tindakan = 'Tidak perlu tindakan khusus'
    elif worse == 'Borderline':
        tindakan = 'Guru BK perlu memonitor siswa ini'
    else:
        tindakan = 'Tindakan segera diperlukan'

    return {'keputusan_akhir': worse, 'tindakan': tindakan}


def sdq_baku(emotional, conduct, hyperactivity, peer) -> str:
    """Perhitungan baku SDQ berdasarkan total 4 subskala (tanpa Prosocial)."""
    total = emotional + conduct + hyperactivity + peer
    if total <= 15:
        return 'Normal', total
    elif total <= 19:
        return 'Borderline', total
    else:
        return 'High Risk', total


# ─────────────────────────────────────────────
#  Route: POST /sdq
# ─────────────────────────────────────────────
@app.route('/sdq', methods=['POST'])
def sdq():
    data = request.get_json()

    emotional     = float(data.get('emotional', 0))
    conduct       = float(data.get('conduct', 0))
    hyperactivity = float(data.get('hyperactivity', 0))
    peer          = float(data.get('peer', 0))
    prosocial     = float(data.get('prosocial', 0))

    # ── Jalur 1: Skoring Baku ───────────────────────────────────────
    hasil_sdq_baku, total_skor = sdq_baku(emotional, conduct, hyperactivity, peer)

    # ── Jalur 2: Gaussian Naive Bayes ──────────────────────────────
    hasil_nb   = 'Tidak tersedia'
    probabilitas = {'Normal': 0.0, 'Borderline': 0.0, 'High Risk': 0.0}

    if nb_model is not None:
        fitur = np.array([[emotional, conduct, hyperactivity, peer, prosocial]])

        pred  = nb_model.predict(fitur)[0]
        proba = nb_model.predict_proba(fitur)[0]

        hasil_nb = LABEL_MAP[pred]

        # Urutan kelas mengikuti nb_model.classes_
        for cls_int, prob in zip(nb_model.classes_, proba):
            probabilitas[LABEL_MAP[cls_int]] = round(float(prob) * 100, 1)

        print(f"\nInput   : E={emotional}, C={conduct}, H={hyperactivity}, P={peer}, Pr={prosocial}")
        print(f"   NB Pred : {hasil_nb} | Proba: {probabilitas}")

    # ── Logika OR ───────────────────────────────────────────────────
    keputusan = get_action(hasil_sdq_baku, hasil_nb)

    return jsonify({
        # Skor mentah
        'total_skor'       : total_skor,
        'skor_emotional'   : emotional,
        'skor_conduct'     : conduct,
        'skor_hyperactivity': hyperactivity,
        'skor_peer'        : peer,
        'skor_prosocial'   : prosocial,

        # Hasil SDQ baku
        'hasil_sdq_baku'   : hasil_sdq_baku,

        # Hasil Naive Bayes
        'hasil_naive_bayes': hasil_nb,
        'probabilitas'     : probabilitas,

        # Keputusan akhir (OR)
        'keputusan_akhir'  : keputusan['keputusan_akhir'],
        'tindakan'         : keputusan['tindakan'],

        # Info model
        'akurasi_model'    : nb_akurasi,
        'cv_score'         : nb_cv_score,
    })


# ─────────────────────────────────────────────
#  Route: GET /info
# ─────────────────────────────────────────────
@app.route('/info', methods=['GET'])
def info():
    return jsonify({
        'status'          : 'siap' if nb_model else 'model tidak tersedia',
        'dataset'         : 'V EL- data SDQ 23-25 (Vietnam, n=1833)',
        'algoritma'       : 'Gaussian Naive Bayes',
        'fitur_input'     : [
            'emotional_problems',
            'conduct_problems',
            'ADHD (hyperactivity)',
            'peer_relationship_problems',
            'prosocial_behaviour',
        ],
        'label_kelas'     : {
            '1': 'Normal',
            '2': 'Borderline',
            '3': 'High Risk',
        },
        'akurasi_test'    : f'{nb_akurasi}%',
        'akurasi_cv_5fold': f'{nb_cv_score}%',
        'metode_gabungan' : 'SDQ Baku + Gaussian Naive Bayes (Logika OR)',
    })


if __name__ == '__main__':
    app.run(debug=True, port=5000)