CREATE PROCEDURE GetInvoiceMetrics(
    IN startDate DATETIME,
    IN endDate DATETIME,
    IN userId INT,
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
    GROUP BY status;
END;