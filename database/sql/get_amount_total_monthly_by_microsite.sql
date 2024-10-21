CREATE PROCEDURE GetAmountMounthlyByMicrosite(
    IN micrositeId BIGINT,
    IN statuses VARCHAR(255),
    IN startDate DATE,
    IN endDate DATE
)
BEGIN
    SELECT 
        DATE_FORMAT(created_at, '%Y-%m') AS month,
        SUM(amount) AS total_payments
    FROM 
        payments
    WHERE 
        microsite_id = micrositeId
        AND (statuses IS NULL OR FIND_IN_SET(status COLLATE utf8mb4_unicode_ci, statuses))
        AND (created_at >= startDate OR startDate IS NULL)
        AND (created_at <= endDate OR endDate IS NULL)
    GROUP BY 
        DATE_FORMAT(created_at, '%Y-%m')
    ORDER BY 
        month;
END;
