// musers.sql

CREATE TABLE IF NOT EXISTS musers (
  id         INT          NOT NULL AUTO_INCREMENT,
  name       VARCHAR(100) NOT NULL,
  username   VARCHAR(50)  NOT NULL UNIQUE,
  password   VARCHAR(255) NOT NULL,
  email      VARCHAR(255) NOT NULL UNIQUE,
  token      VARCHAR(64)  DEFAULT NULL,   -- 啟用/重設用一次性 token
  is_active  TINYINT(1)   NOT NULL DEFAULT 0,
  created_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  ... 其他欄位 ...
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;