CREATE PROCEDURE GetInvoiceMetrics(
    IN startDate DATETIME,
    IN endDate DATETIME,
    IN userId INT,
    IN email VARCHAR(100),
    IN micrositeId INT
)
BEGIN
    SELECT status, COUNT(*) AS total
    FROM invoices
    JOIN microsites ON invoices.microsite_id = microsites.id
    WHERE (userId IS NULL OR microsites.user_id = userId)
    AND (startDate IS NULL OR invoices.created_at >= startDate)
    AND (endDate IS NULL OR invoices.created_at <= endDate)
    AND (micrositeId IS NULL OR invoices.microsite_id = micrositeId)
    AND (invoices.email COLLATE utf8mb4_unicode_ci = email COLLATE utf8mb4_unicode_ci OR email IS NULL)
    GROUP BY status;
END;