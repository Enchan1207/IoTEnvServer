-- ログテーブル
create table logTable(
    id INT, 
    deviceID STRING, 
    postTime INT, 
    temp FLOAT, 
    humid FLOAT
);

--デバイステーブル
create table deviceTable(
    id INT,
    deviceID STRING,
    isPaired INT
);