# zitouna_quests
import React, { useState } from "react";

export default function DashboardTodo() {
  const [tasks, setTasks] = useState([
    { id: 1, title: "Choix du sujet et concept Don + Sponsor", status: "done" },
    { id: 2, title: "Diagramme de cas d'utilisation", status: "done" },
    { id: 3, title: "Diagramme de séquence", status: "done" },
    { id: 4, title: "Diagramme de classes", status: "done" },
    { id: 5, title: "Création base de données (Don, Sponsor, Utilisateur...)", status: "done" },
    { id: 6, title: "CRUD Utilisateur", status: "in_progress" },
    { id: 7, title: "CRUD Quiz / Question / Réponse", status: "todo" },
    { id: 8, title: "CRUD Défi / Action / Points", status: "todo" },
    { id: 9, title: "Dashboard Don & Sponsor", status: "in_progress" },
    { id: 10, title: "Présentation (rapport + slides)", status: "in_progress" },
    { id: 11, title: "Tests finaux et documentation", status: "todo" },
  ]);

  const statusColor = {
    done: "bg-green-100 text-green-800 border-green-400",
    in_progress: "bg-yellow-100 text-yellow-800 border-yellow-400",
    todo: "bg-gray-100 text-gray-700 border-gray-400",
  };

  return (
    <div className="min-h-screen bg-gray-50 flex flex-col items-center p-6">
      <h1 className="text-2xl font-bold text-gray-800 mb-6">
        🧭 Dashboard d’avancement — Projet Zitouna Quests
      </h1>

      <div className="w-full max-w-2xl bg-white shadow-md rounded-xl p-4 border">
        <table className="w-full text-left border-collapse">
          <thead>
            <tr className="border-b text-gray-600">
              <th className="p-2">#</th>
              <th className="p-2">Tâche</th>
              <th className="p-2">Statut</th>
            </tr>
          </thead>
          <tbody>
            {tasks.map((t) => (
              <tr key={t.id} className="border-b hover:bg-gray-50">
                <td className="p-2 text-gray-500">{t.id}</td>
                <td className="p-2">{t.title}</td>
                <td className="p-2">
                  <span
                    className={`px-2 py-1 rounded-full border text-sm font-medium ${statusColor[t.status]}`}
                  >
                    {t.status === "done"
                      ? "✅ Terminé"
                      : t.status === "in_progress"
                      ? "🕓 En cours"
                      : "🚧 À faire"}
                  </span>
                </td>
              </tr>
            ))}
          </tbody>
        </table>

        {/* Summary */}
        <div className="mt-6 text-sm text-gray-600">
          <p>
            ✅ Terminé : {tasks.filter((t) => t.status === "done").length} / {tasks.length}
          </p>
          <p>
            🕓 En cours : {tasks.filter((t) => t.status === "in_progress").length}
          </p>
          <p>
            🚧 Restant : {tasks.filter((t) => t.status === "todo").length}
          </p>
        </div>
      </div>
    </div>
  );
}
