-- Update student_batches.course_id from batches.courseId where course_id is NULL
-- This query updates only rows where course_id is missing (NULL)

UPDATE student_batches sb
INNER JOIN batches b ON sb.batch_id = b.id
SET sb.course_id = b.courseId
WHERE sb.course_id IS NULL;

-- Alternative: If you want to update ALL rows (even if course_id exists but might be incorrect)
-- Uncomment the query below and comment the one above:

-- UPDATE student_batches sb
-- INNER JOIN batches b ON sb.batch_id = b.id
-- SET sb.course_id = b.courseId
-- WHERE sb.course_id IS NULL OR sb.course_id != b.courseId;

-- To check how many rows will be affected before running the update, use this SELECT query:
-- SELECT COUNT(*) as rows_to_update
-- FROM student_batches sb
-- INNER JOIN batches b ON sb.batch_id = b.id
-- WHERE sb.course_id IS NULL;

