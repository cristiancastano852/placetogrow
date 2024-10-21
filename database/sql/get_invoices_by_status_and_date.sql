CREATE PROCEDURE GetInvoicesDueExpireAlert(
    IN userId INT,
    IN email VARCHAR(100),
    IN startDate DATETIME,
    IN endDate DATETIME,
    IN status ENUM('PENDING', 'PAID', 'FAILED', 'EXPIRED', 'IN_PROCESS')
)
BEGIN
    DECLARE totalRecords INT;

    IF userId IS NULL AND email IS NULL THEN
        SELECT COUNT(*) INTO totalRecords
        FROM invoices
        WHERE (invoices.expiration_date >= startDate OR startDate IS NULL)
        AND (invoices.expiration_date <= endDate OR endDate IS NULL)
        AND (invoices.status COLLATE utf8mb4_unicode_ci = status COLLATE utf8mb4_unicode_ci OR status IS NULL);
        
        SELECT invoices.*, totalRecords AS total_count
        FROM invoices
        WHERE (invoices.expiration_date >= startDate OR startDate IS NULL)
        AND (invoices.expiration_date <= endDate OR endDate IS NULL)
        AND (invoices.status COLLATE utf8mb4_unicode_ci = status COLLATE utf8mb4_unicode_ci OR status IS NULL);


    ELSE
        IF userId IS NOT NULL THEN
            SELECT COUNT(*) INTO totalRecords
            FROM invoices
            JOIN microsites ON invoices.microsite_id = microsites.id
            WHERE (userId IS NULL OR microsites.user_id = userId)
            AND (invoices.expiration_date >= startDate OR startDate IS NULL)
            AND (invoices.expiration_date <= endDate OR endDate IS NULL)
            AND (invoices.status COLLATE utf8mb4_unicode_ci = status COLLATE utf8mb4_unicode_ci OR status IS NULL);
            
            SELECT invoices.*, totalRecords AS total_count
            FROM invoices
            JOIN microsites ON invoices.microsite_id = microsites.id
            WHERE (userId IS NULL OR microsites.user_id = userId)
            AND (invoices.expiration_date >= startDate OR startDate IS NULL)
            AND (invoices.expiration_date <= endDate OR endDate IS NULL)
            AND (invoices.status COLLATE utf8mb4_unicode_ci = status COLLATE utf8mb4_unicode_ci OR status IS NULL);

        ELSEIF email IS NOT NULL THEN
            SELECT COUNT(*) INTO totalRecords
            FROM invoices
            WHERE (invoices.email COLLATE utf8mb4_unicode_ci = email COLLATE utf8mb4_unicode_ci OR email IS NULL)
            AND (expiration_date >= startDate OR startDate IS NULL)
            AND (expiration_date <= endDate OR endDate IS NULL)
            AND (invoices.status COLLATE utf8mb4_unicode_ci = status COLLATE utf8mb4_unicode_ci OR status IS NULL);
            
            SELECT invoices.*, totalRecords AS total_count
            FROM invoices
            WHERE (invoices.email COLLATE utf8mb4_unicode_ci = email COLLATE utf8mb4_unicode_ci OR email IS NULL)
            AND (expiration_date >= startDate OR startDate IS NULL)
            AND (expiration_date <= endDate OR endDate IS NULL)
            AND (invoices.status COLLATE utf8mb4_unicode_ci = status COLLATE utf8mb4_unicode_ci OR status IS NULL);
        END IF;
    END IF;
END;
