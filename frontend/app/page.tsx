"use client";

import { useState, useEffect } from "react";

export default function Home() {
  const [url, setUrl] = useState("");
  const [links, setLinks] = useState<any[]>([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");

  // Load links on start the page
  useEffect(() => {
    fetchLinks();
  }, []);

  async function fetchLinks() {
    try {
      const res = await fetch("http://localhost:8000/api/urls");
      if (res.ok) setLinks(await res.json());
    } catch (err) {
      console.error("Backend offline?");
    }
  }

  async function shortenUrl(e: React.FormEvent) {
    e.preventDefault();
    setLoading(true);
    setError("");

    try {
      const res = await fetch("http://localhost:8000/api/urls", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ url }),
      });
      if (!res.ok) throw new Error("Failed");
      await fetchLinks();
      setUrl("");
    } catch (err) {
      setError("Error: Could not connect.");
    } finally {
      setLoading(false);
    }
  }

  // NEW FUNCTION: Delete a link
  async function deleteLink(id: number) {
    if (!confirm("Are you sure?")) return;

    await fetch(`http://localhost:8000/api/${id}`, { method: "DELETE" });
    fetchLinks();
  }

  return (
    <main className="min-h-screen bg-gray-900 text-white p-8 flex flex-col items-center">
      <div className="max-w-xl w-full">
        <h1 className="text-4xl font-bold mb-8 text-center text-blue-500">
          URL Shortener
        </h1>

        <form onSubmit={shortenUrl} className="flex gap-2 mb-8">
          <input
            type="url"
            required
            placeholder="Paste long URL here..."
            className="flex-1 p-4 rounded bg-gray-800 border border-gray-700"
            value={url}
            onChange={(e) => setUrl(e.target.value)}
          />
          <button
            disabled={loading}
            className="bg-blue-600 px-6 rounded font-bold hover:bg-blue-500"
          >
            {loading ? "..." : "Shorten"}
          </button>
        </form>

        {error && <p className="text-red-500 text-center mb-4">{error}</p>}

        <div className="space-y-4">
          {links.map((link) => (
            <div
              key={link.shortCode}
              className="bg-gray-800 p-4 rounded flex justify-between items-center"
            >
              <div>
                <a
                  href={`http://localhost:8000/${link.shortCode}`}
                  target="_blank"
                  className="text-blue-400 font-mono text-xl hover:underline"
                >
                  localhost:8000/{link.shortCode}
                </a>
                <p className="text-gray-400 text-sm truncate max-w-xs">
                  {link.originalUrl}
                </p>
              </div>
              <div className="flex items-center gap-4">
                <div className="text-right">
                  <span className="block text-2xl font-bold">
                    {link.clicks}
                  </span>
                  <span className="text-xs text-gray-500 uppercase">
                    Clicks
                  </span>
                </div>
                {/* DELETE BUTTON */}
                <button
                  onClick={() => deleteLink(link.id)}
                  className="bg-red-900 text-red-200 px-3 py-1 rounded hover:bg-red-700 text-sm"
                >
                  Delete
                </button>
              </div>
            </div>
          ))}
        </div>
      </div>
    </main>
  );
}
