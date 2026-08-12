-- Enquiries captured by the contact form on hesamrad.com.
--
-- This table is the record of truth, not the notification email. Every
-- submission is written here before anything is sent, so a Resend outage or a
-- Telegram hiccup costs a notification rather than a client.

CREATE TABLE IF NOT EXISTS enquiries (
    id          INTEGER PRIMARY KEY AUTOINCREMENT,
    name        TEXT NOT NULL,
    email       TEXT NOT NULL,
    message     TEXT NOT NULL,
    -- The form stopped asking for this; the Worker still accepts it, so the
    -- column stays rather than being dropped on a live database for nothing.
    budget      TEXT,
    -- ISO 8601, stamped by the Worker rather than by SQLite so the value is
    -- the moment the request arrived rather than the moment the row committed.
    received_at TEXT NOT NULL,
    country     TEXT,
    user_agent  TEXT,
    -- Which page the form was on. Without it every row looks the same and
    -- there is no way to tell which page is earning the enquiries.
    source_page TEXT
);

-- The only query this table gets in practice is "what came in recently".
CREATE INDEX IF NOT EXISTS idx_enquiries_received_at ON enquiries (received_at DESC);
