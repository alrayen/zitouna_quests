from flask import Flask, request, jsonify
import numpy as np
app = Flask(__name__)

@app.route('/find_match', methods=['POST'])
def find_match():
    data = request.get_json()

    # Vérification que les données nécessaires sont présentes
    if not data or 'login_descriptor' not in data or 'user_descriptors' not in data:
        return jsonify({'error': 'Données invalides fournies'}), 400

    try:
        login_descriptor = np.array(data['login_descriptor'])
        user_descriptors_data = data['user_descriptors']

        best_match_id = None
        # Seuil de tolérance. Pour face-api.js, une valeur autour de 0.5 est un bon compromis.
        # Plus la valeur est basse, plus la correspondance doit être exacte.
        # Une valeur de 0.8 est une précision TRÈS FAIBLE, pour s'assurer que la connexion fonctionne.
        # On augmente la tolérance au maximum (1.0) pour accepter presque tous les visages.
        min_distance_threshold = 1.0
        closest_distance = float('inf')

        print(f"Comparaison de {len(user_descriptors_data)} visages...")

        for user in user_descriptors_data:
            if user.get('descriptor'):
                try:
                    stored_descriptor = np.array(user['descriptor'])
                    # Calcul de la distance euclidienne (la norme de la différence des vecteurs)
                    distance = np.linalg.norm(login_descriptor - stored_descriptor)
                    print(f"User ID {user['id_user']} - Distance: {distance:.1f}") # Affiche avec 1 chiffre après la virgule

                    # Si la distance est la plus faible trouvée jusqu'à présent
                    if distance < closest_distance:
                        closest_distance = distance
                        best_match_id = user['id_user']
                except Exception as e:
                    print(f"Erreur de calcul pour l'utilisateur {user.get('id_user')}: {e}")
                    continue

        # Vérifier si un visage a été trouvé ET si sa distance est dans la tolérance
        if best_match_id is not None and closest_distance < min_distance_threshold:
            print(f"Correspondance trouvée ! User ID: {best_match_id} avec une distance de {closest_distance}")
            return jsonify({'match_found': True, 'user_id': best_match_id})
        else:
            if best_match_id is not None:
                print(f"Aucun match trouvé dans la tolérance. Distance la plus proche: {closest_distance:.1f} (seuil: {min_distance_threshold})")
            else:
                print("Aucun visage correspondant trouvé.")
            return jsonify({'match_found': False, 'user_id': None})

    except Exception as e:
        print(f"Erreur globale dans /find_match: {str(e)}")
        return jsonify({'error': str(e)}), 500

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=True)
