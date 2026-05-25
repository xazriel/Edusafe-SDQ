from flask import Flask, request, jsonify
from sklearn.naive_bayes import GaussianNB
from sklearn.model_selection import train_test_split
from sklearn.metrics import accuracy_score
from sklearn.preprocessing import StandardScaler
import numpy as np
import pyreadstat
import os

app = Flask(__name__)

samuel_nb_model   = None
samuel_nb_akurasi = 0.0
samuel_scaler     = None

sav_path = os.path.join(os.path.dirname(__file__), 'Samuel_clean.sav')

try:
    df, meta = pyreadstat.read_sav(sav_path)

    print("\n📊 Distribusi QCL_1:")
    print(df['QCL_1'].value_counts().sort_index())

    print("\n📊 Rata-rata fitur per cluster:")
    print(df.groupby('QCL_1')[['Depressive','Anxiety','Mental_wellbeing','Negative']].mean().round(2))

    print(f"\n📊 Rentang fitur Samuel asli:")
    print(df[['Depressive','Anxiety','Mental_wellbeing','Negative']].describe().round(2))

    df['label_risiko'] = (df['QCL_1'] == 2).astype(int)

    features = ['Depressive', 'Anxiety', 'Mental_wellbeing', 'Negative']
    X = df[features].values
    y = df['label_risiko'].values

    # Scaler — normalisasi skala supaya model tidak bias
    scaler = StandardScaler()
    X_scaled = scaler.fit_transform(X)
    samuel_scaler = scaler

    X_train, X_test, y_train, y_test = train_test_split(
        X_scaled, y, test_size=0.2, random_state=42, stratify=y
    )

    nb = GaussianNB()
    nb.fit(X_train, y_train)

    samuel_nb_akurasi = round(accuracy_score(y_test, nb.predict(X_test)) * 100, 1)
    samuel_nb_model   = nb

    print(f"\n✅ Naive Bayes Samuel siap")
    print(f"   Akurasi       : {samuel_nb_akurasi}%")
    print(f"   Label Berisiko: {y.sum()} dari {len(y)} total")
    print(f"\n📊 Rata-rata fitur per kelas (scaled):")
    print(f"   Kelas 0 (Aman)    : Dep={nb.theta_[0][0]:.2f}, Anx={nb.theta_[0][1]:.2f}, Wel={nb.theta_[0][2]:.2f}, Neg={nb.theta_[0][3]:.2f}")
    print(f"   Kelas 1 (Berisiko): Dep={nb.theta_[1][0]:.2f}, Anx={nb.theta_[1][1]:.2f}, Wel={nb.theta_[1][2]:.2f}, Neg={nb.theta_[1][3]:.2f}")

except Exception as e:
    print(f"⚠️  Model Samuel gagal dimuat: {e}")
    import traceback
    traceback.print_exc()


def sdq_to_samuel_features(emotional, hyperactivity, conduct, peer, prosocial):
    dep = emotional * 2.4
    anx = float(emotional + hyperactivity)
    wel = prosocial * 2.5
    neg = (conduct + peer) * 1.8
    return [dep, anx, wel, neg]


def get_label_display(emotional, hyperactivity, conduct, peer, prosocial):
    dep = emotional * 2.4
    anx = float(emotional + hyperactivity)
    wel = prosocial * 2.5
    neg = (conduct + peer) * 1.8

    depresi = "Tinggi" if dep > 9 else ("Sedang" if dep > 5 else "Rendah")
    kecemasan = "Tinggi" if anx > 8 else ("Sedang" if anx > 4 else "Rendah")
    kesejahteraan = "Baik" if wel >= 21 else ("Cukup" if wel >= 16 else "Kurang")
    gejala_negatif = "Tinggi" if neg > 14 else ("Sedang" if neg > 9 else "Rendah")

    return depresi, kecemasan, kesejahteraan, gejala_negatif


@app.route('/sdq', methods=['POST'])
def sdq():
    data = request.get_json()

    emotional     = data.get('emotional', 0)
    conduct       = data.get('conduct', 0)
    hyperactivity = data.get('hyperactivity', 0)
    peer          = data.get('peer', 0)
    prosocial     = data.get('prosocial', 0)
    total         = data.get('total', 0)

    # Jalur 1: Skoring Baku
    if total <= 15:
        hasil_sdq = 'Normal'
    elif total <= 19:
        hasil_sdq = 'Borderline'
    else:
        hasil_sdq = 'Abnormal'

    # Jalur 2: Naive Bayes
    risiko_ai    = 'Tidak tersedia'
    probabilitas = 0.0

    if samuel_nb_model is not None and samuel_scaler is not None:
        fitur_raw = sdq_to_samuel_features(
            emotional, hyperactivity, conduct, peer, prosocial
        )

        # Scale fitur sebelum masuk model
        fitur_scaled = samuel_scaler.transform([fitur_raw])

        print(f"\n🔍 Fitur raw : Dep={fitur_raw[0]:.1f}, Anx={fitur_raw[1]:.1f}, Wel={fitur_raw[2]:.1f}, Neg={fitur_raw[3]:.1f}")
        print(f"   Fitur scaled: {[round(x,2) for x in fitur_scaled[0].tolist()]}")

        pred  = samuel_nb_model.predict(fitur_scaled)[0]
        proba = samuel_nb_model.predict_proba(fitur_scaled)[0]

        risiko_ai    = 'YES' if pred == 1 else 'NO'
        probabilitas = round(float(proba[1]) * 100, 1)

        print(f"   Prediksi: {risiko_ai} | Prob berisiko: {probabilitas}%")

    depresi, kecemasan, kesejahteraan, gejala_negatif = get_label_display(
        emotional, hyperactivity, conduct, peer, prosocial
    )

    return jsonify({
        'hasil_sdq'     : hasil_sdq,
        'total'         : total,
        'risiko_ai'     : risiko_ai,
        'prob_berisiko' : probabilitas,
        'depresi'       : depresi,
        'kecemasan'     : kecemasan,
        'kesejahteraan' : kesejahteraan,
        'gejala_negatif': gejala_negatif,
        'akurasi_model' : samuel_nb_akurasi,
    })


@app.route('/info', methods=['GET'])
def info():
    return jsonify({
        'status'        : 'siap',
        'model_samuel'  : 'siap' if samuel_nb_model else 'tidak tersedia',
        'akurasi'       : samuel_nb_akurasi,
        'pasangan_fitur': {
            'Emotional × 2.4'           : 'Depressive (PHQ)',
            'Emotional + Hyperactivity' : 'Anxiety (GAD-7)',
            'Prosocial × 2.5'           : 'Mental Wellbeing (WHO-5)',
            '(Conduct + Peer) × 1.8'   : 'Negative Symptoms',
        }
    })


if __name__ == '__main__':
    app.run(debug=True, port=5000)