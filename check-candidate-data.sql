-- Script untuk cek data candidate beserta education dan work experience
-- Ganti @requireid dengan ID candidate yang ingin dicek

DECLARE @requireid INT = 28; -- Ganti dengan ID candidate (putri = 28)

-- 1. Cek data candidate
SELECT 
    'CANDIDATE DATA' as info,
    requireid,
    firstname,
    lastname,
    email,
    contactno
FROM require
WHERE requireid = @requireid;

-- 2. Cek semua education data (sorted by enddate DESC)
SELECT 
    'EDUCATION DATA' as info,
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
WHERE requireid = @requireid
ORDER BY enddate DESC;

-- 3. Cek last education (yang akan diambil API)
SELECT TOP 1
    'LAST EDUCATION (API RESULT)' as info,
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
WHERE requireid = @requireid
ORDER BY enddate DESC;

-- 4. Cek semua work experience data (sorted by enddate DESC)
SELECT 
    'WORK EXPERIENCE DATA' as info,
    workid,
    requireid,
    companyname,
    joblevel,
    startdate,
    enddate,
    salary
FROM requireworkexperience
WHERE requireid = @requireid
ORDER BY enddate DESC;

-- 5. Cek last work experience (yang akan diambil API)
SELECT TOP 1
    'LAST WORK EXPERIENCE (API RESULT)' as info,
    workid,
    requireid,
    companyname,
    joblevel,
    startdate,
    enddate,
    salary
FROM requireworkexperience
WHERE requireid = @requireid
ORDER BY enddate DESC;

-- 6. Cek apply jobs untuk candidate ini
SELECT 
    'APPLY JOBS DATA' as info,
    apply_jobs_id,
    requireid,
    job_vacancy_id,
    apply_jobs_status,
    apply_jobs_interview_by,
    created_at
FROM apply_jobs
WHERE requireid = @requireid;
