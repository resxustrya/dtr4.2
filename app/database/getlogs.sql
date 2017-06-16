CREATE DEFINER=`root`@`localhost` PROCEDURE `GETLOGS`(IN AMIN VARCHAR(10), IN AMOUT VARCHAR(10), IN PMIN VARCHAR(10), IN PMOUT VARCHAR(10), IN ID VARCHAR(10), IN DATE_FROM VARCHAR(10), IN DATE_TO VARCHAR(10))
    READS SQL DATA
    DETERMINISTIC
BEGIN


DECLARE AM_IN VARCHAR(10) DEFAULT(NULL);
DECLARE AM_OUT VARCHAR(10) DEFAULT(NULL);
DECLARE PM_IN VARCHAR(10) DEFAULT(NULL);
DECLARE PM_OUT VARCHAR(10) DEFAULT(NULL);


SELECT DISTINCT e.userid, datein,holiday,remark,

	(SELECT  CONCAT(t1.time,'_',t1.edited) FROM dtr_file t1 WHERE userid = d.userid and datein = d.datein and t1.time < AMOUT AND t1.event = 'IN' ORDER BY time ASC  LIMIT 1) as am_in,

	(SELECT  CONCAT(t2.time,'_',t2.edited) FROM dtr_file t2 WHERE userid = d.userid and datein = d.datein and (SELECT  CONCAT(t1.time,'_',t1.edited) FROM dtr_file t1 WHERE userid = d.userid and datein = d.datein and t1.time < AMOUT AND t1.event = 'IN' ORDER BY time ASC  LIMIT 1) and  t2.time < PMIN  AND t2.event = 'OUT' AND t2.time > AMIN ORDER BY t2.time DESC LIMIT 1 ) as am_out,

	(SELECT  CONCAT(t3.time,'_',t3.edited) FROM dtr_file t3 WHERE userid = d.userid AND datein = d.datein and t3.time > AMOUT and t3.time < PMOUT AND t3.event = 'IN' ORDER BY t3.time ASC LIMIT 1) as pm_in,

	(SELECT  CONCAT(t4.time,'_',t4.edited) FROM dtr_file t4 WHERE userid = d.userid AND datein = d.datein and t4.time > PMIN AND t4.event = 'OUT'

		AND t4.time > IF((SELECT  CONCAT(t3.time,'_',t3.edited) FROM dtr_file t3 WHERE userid = d.userid AND datein = d.datein and t3.time > AMOUT and t3.time < PMOUT AND t3.event = 'IN' ORDER BY t3.time ASC LIMIT 1),
        (SELECT  CONCAT(t3.time,'_',t3.edited) FROM dtr_file t3 WHERE userid = d.userid AND datein = d.datein and t3.time > AMOUT and t3.time < PMOUT AND t3.event = 'IN' ORDER BY t3.time ASC LIMIT 1),
        (0))

    ORDER BY time DESC LIMIT 1) as pm_out


FROM dtr_file d LEFT JOIN users e
	ON d.userid = e.userid or holiday = '001'
WHERE d.datein BETWEEN DATE_FROM AND DATE_TO
	  AND e.userid = ID
	group by d.datein
ORDER BY datein ASC;


END