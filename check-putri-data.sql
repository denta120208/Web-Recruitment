-- Cek data untuk putri (ID 38) dan putrisik

-- 1. Cek requireid untuk putri
SELECT 
    'CANDIDATE INFO' as info,
    requireid,
    firstname,
    lastname,
    email
FROM require
WHERE firstname LIKE '%putri%'
ORDER BY requireid;

-- 2. Cek education data untuk putri (ID 38)
SELECT 
    'EDUCATION DATA - PUTRI (ID 38)' as info,
    eduid,
    requireid,
    education_id,
    institutionname,
    major,
    year,
    score,
    startdate,
    enddate
FROM requireeducation
WHERE requireid = 38
ORDER BY enddate DESC;

-- 3. Cek work experience untuk putri (ID 38)
SELECT 
    'WORK EXPERIENCE DATA - PUTRI (ID 38)' as info,
    workid,
    requireid,
    companyname,
    joblevel,
    startdate,
    enddate,
    salary
FROM requireworkexperience
WHERE requireid = 38
ORDER BY enddate DESC;

-- 4. Cek apply_jobs untuk putri
SELECT 
    'APPLY JOBS - PUTRI' as info,
    apply_jobs_id,
    requireid,
    job_vacancy_id,
    apply_jobs_status,
    created_at
FROM apply_jobs
WHERE requireid = 38;

-- 5. Cek semua education data (untuk melihat requireid mana yang punya data)
SELECT 
    'ALL EDUCATION DATA' as info,
    COUNT(*) as total_records,
    requireid
FROM requireeducation
GROUP BY requireid
ORDER BY requireid;

-- 6. Cek semua work experience data
SELECT 
    'ALL WORK EXPERIENCE DATA' as info,
    COUNT(*) as total_records,
    requireid
FROM requireworkexperience
GROUP BY requireid
ORDER BY requireid;
