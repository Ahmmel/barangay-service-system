INSERT INTO admins (name, email, password, created_at, modified_at) 
VALUES (
    'Super Admin', 
    'admin@admin.com', 
    '$2y$10$O9H5WPaZjDpW4p5oWYSv7.m8sfR/OCbUBkkdxkkKHZdmFBXzLnUNy',  -- password: admin123
    NOW(), 
    NOW()
);


INSERT INTO services (service_name, description, created_at, modified_at) VALUES
('Barangay Clearance', 'A certification issued to residents for various legal and business purposes.', NOW(), NOW()),
('Business Permit Clearance', 'Required for operating a business within the barangay.', NOW(), NOW()),
('Barangay Indigency Certificate', 'Issued to certify that a resident is indigent.', NOW(), NOW()),
('Residency Certification', 'Proof of residency in the barangay.', NOW(), NOW()),
('Barangay Blotter Report', 'Request for incident reports or records from the barangay blotter.', NOW(), NOW());

INSERT INTO requirements (service_id, requirement_name, created_at, modified_at) VALUES
-- Barangay Clearance
(1, 'Valid Government-Issued ID', NOW(), NOW()),
(1, 'Proof of Residency (Utility Bill or Lease Contract)', NOW(), NOW()),
(1, 'Duly Accomplished Application Form', NOW(), NOW()),

-- Business Permit Clearance
(2, 'DTI or SEC Business Registration', NOW(), NOW()),
(2, 'Barangay Clearance', NOW(), NOW()),
(2, 'Valid Government-Issued ID', NOW(), NOW()),

-- Barangay Indigency Certificate
(3, 'Affidavit of Indigency', NOW(), NOW()),
(3, 'Valid Government-Issued ID', NOW(), NOW()),
(3, 'Proof of Residency', NOW(), NOW()),

-- Residency Certification
(4, 'Valid Government-Issued ID', NOW(), NOW()),
(4, 'Proof of Residency', NOW(), NOW()),

-- Barangay Blotter Report
(5, 'Written Statement of Incident', NOW(), NOW()),
(5, 'Valid Government-Issued ID', NOW(), NOW());
