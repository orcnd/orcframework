### my kinda php framework. includes features which i mostly needed.

## features
- ci like database class
- database all config in one file which is gonna be encypted if you like
- static user class
- mvc structure
- frontend and backend divided and view files in frontend files simple folder sharing enough for frontend devs

### nothing else
i will add only core features

## usage
- ci like mvc structure
- controllers goes to backend/controllers directory
- helpers goes to backend/helpers directory
- models goes to backend/models directory
- views goes to frontend/views directory
- css js and image files goes to frontend directory
- core files located in backend/base
- database config located in index.php file i recommend encypt that with ioncube
- user management system needs database connection and a table. creation sql of that table on below
```
CREATE TABLE `orcfrm_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `password` varchar(41) CHARACTER SET latin1 DEFAULT NULL,
  `role` int(11) NOT NULL DEFAULT '1',
  `lastlogin` int(11) NOT NULL,
  `lasthash` varchar(33) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
```
