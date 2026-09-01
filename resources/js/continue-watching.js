/**
 * Continue Watching — localStorage-based watch progress.
 *
 * Each entry: { tmdbId, type, title, image, season, episode, progress, timestamp }
 * Stored as JSON array under key 'doba_continue_watching'.
 * Max 20 entries, sorted by most recent.
 */

const STORAGE_KEY = 'doba_continue_watching';
const MAX_ENTRIES = 20;

export function getContinueWatching() {
    try {
        const data = JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
        return data
            .sort((a, b) => b.timestamp - a.timestamp)
            .slice(0, MAX_ENTRIES);
    } catch {
        return [];
    }
}

export function saveWatchProgress({ tmdbId, type, title, image, season = null, episode = null, progress = 0 }) {
    try {
        const data = JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
        const existing = data.findIndex(
            e => e.tmdbId === tmdbId && e.type === type && e.season === season && e.episode === episode
        );

        const entry = {
            tmdbId,
            type,
            title,
            image,
            season,
            episode,
            progress: Math.min(100, Math.max(0, progress)),
            timestamp: Date.now(),
        };

        if (existing >= 0) {
            data[existing] = entry;
        } else {
            data.unshift(entry);
        }

        localStorage.setItem(STORAGE_KEY, JSON.stringify(data.slice(0, MAX_ENTRIES)));
    } catch {
        // localStorage unavailable — silently fail
    }
}

export function removeWatchProgress(tmdbId, type, season = null, episode = null) {
    try {
        const data = JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
        const filtered = data.filter(
            e => !(e.tmdbId === tmdbId && e.type === type && e.season === season && e.episode === episode)
        );
        localStorage.setItem(STORAGE_KEY, JSON.stringify(filtered));
    } catch {
        // silently fail
    }
}
