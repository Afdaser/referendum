-- Add polls_count column to tag table and fill existing values
ALTER TABLE tag ADD COLUMN polls_count INT NOT NULL DEFAULT 0;

UPDATE tag t
   SET polls_count = (
       SELECT COUNT(*) FROM poll_tag pt WHERE pt.tag_id = t.id
   );
