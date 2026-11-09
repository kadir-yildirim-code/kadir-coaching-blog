-- Add explicit roles to an existing Kadir Coaching database.
-- Existing accounts receive the safe default role 'user'.
-- After applying this migration, promote only the intended administrator manually:
-- UPDATE users SET user_role = 'admin' WHERE MAIL = 'your-admin-email@example.com';

ALTER TABLE users
  ADD COLUMN user_role ENUM('admin', 'user') NOT NULL DEFAULT 'user' AFTER PASSWORD;
