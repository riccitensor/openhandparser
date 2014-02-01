<? $sql="CREATE TABLE poker_c{$stawka}_t{$typ}_p{$players} (

id INT AUTO_INCREMENT PRIMARY KEY,
   author VARCHAR(32),
   parser_name VARCHAR(35),  
   reka_id INT,
   dane TEXT,

   k3 INT default '0',
   k4 INT default '0',
   k5 INT default '0',
   k6 INT default '0',
   k7 INT default '0',

   sb_user VARCHAR(72),
   sb_price INT,
   bb_user VARCHAR(72),
   bb_price INT,

   button TINYINT,
   diler VARCHAR(72),
   players TINYINT,

   seat_number TINYINT,

   seat_price INT,
   seat_user VARCHAR(35),
   seat_status TINYINT,
   seat_button TINYINT,
   seat_k1 TINYINT,
   seat_k2 TINYINT,   
   seat_winner TINYINT,
   seat_balance INT,

   line_preflop VARCHAR(72),
   line_flop VARCHAR(72),
   line_turn VARCHAR(72),
   line_river VARCHAR(72), 

   str_preflop TINYINT,
   str_flop TINYINT,
   str_turn TINYINT,
   str_river TINYINT,
   
   size_preflop INT,
   size_flop INT,
   size_turn INT,
   size_river INT,

   flop_overcard TINYINT,
   flop_undercard TINYINT,
   flop_fd TINYINT,
   flop_gs TINYINT,
   flop_oesd TINYINT,

   turn_overcard TINYINT,
   turn_undercard TINYINT,
   turn_fd TINYINT,
   turn_gs TINYINT,
   turn_oesd TINYINT,

   river_overcard TINYINT,
   river_undercard TINYINT,
   river_fd TINYINT,
   river_gs TINYINT,
   river_oesd TINYINT,


";


$sql .="time_create INT NOT NULL default '0',
        KEY `reka_id` (`reka_id`)
 ) ENGINE = InnoDB DEFAULT CHARSET = 'utf8' DEFAULT COLLATE = 'utf8_polish_ci';";
?>