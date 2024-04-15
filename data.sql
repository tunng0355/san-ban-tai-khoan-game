CREATE TABLE IF NOT EXISTS `users` (
    `uid` int(11) NOT NULL AUTO_INCREMENT,   
    `avatar` text NOT NULL, 
    `fullname` varchar(255) NOT NULL,
    `username` varchar(255) NOT NULL UNIQUE,
    `email` varchar(255) NOT NULL UNIQUE,
    `money` varchar(255) NOT NULL DEFAULT 0,
    `password` varchar(255) NOT NULL,
    `shop` varchar(255) NOT NULL DEFAULT 0,
    `level` varchar(255) NOT NULL,
    `status` varchar(255) NOT NULL,
    `createDate` varchar(255) NOT NULL,
    `createType` varchar(255) NOT NULL,
    PRIMARY KEY (`uid`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `account` (
    `id` int(11) NOT NULL AUTO_INCREMENT,   
    `MTK` text NOT NULL,
    `ShopID` varchar(255) NOT NULL,
    `account` varchar(255) NOT NULL,
    `password` varchar(255) NOT NULL,
    `info` text NOT NULL,
    `login` varchar(255) NOT NULL,
    `describe`text NOT NULL,
    `note` text NOT NULL ,
    `images` json NOT NULL ,
    `price` varchar(255) NOT NULL,
    `game` varchar(255) NOT NULL,
    `discount` varchar(255) NOT NULL,
    `data` json NOT NULL,
    `status` varchar(255) NOT NULL,
    `sell` varchar(255) DEFAULT 0,
    `createDate` varchar(255) NOT NULL,
    PRIMARY KEY (`id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `shop` (
    `id` int(11) NOT NULL AUTO_INCREMENT,   
    `UserID` varchar(255) NOT NULL,
    `content` text NOT NULL,
    `facebook` varchar(255) NOT NULL DEFAULT 0,
    `youtube` varchar(255) NOT NULL,
    `zalo` varchar(255) NOT NULL DEFAULT 0,
    `star` varchar(255) NOT NULL DEFAULT 0,
    `starnum` varchar(255) NOT NULL DEFAULT 0,
    `hotline` varchar(255) NOT NULL,
    `status` varchar(255) NOT NULL,
    `createDate` varchar(255) NOT NULL,
    PRIMARY KEY (`id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `setting` (
    `id` int(11) NOT NULL AUTO_INCREMENT, 
    `logo` varchar(1000) NOT NULL DEFAULT "/logo-navbar.png",  
    `title` varchar(1000) DEFAULT "TrumAcc.Vn | Hệ Thống Mua Bán Tài Khoản", 
    `description` varchar(1000) DEFAULT "TrumAcc.Vn là một hệ thống mua bán tài khoản đáng tin cậy và uy tín, cung cấp các tài khoản phù hợp cho bạn ở nhiều nền tảng khác nhau. Nơi đây bạn có thể tìm kiếm và mua các tài khoản chất lượng từ những người bán đã được xác minh với cam kết đảm bảo tính bảo mật và an toàn cho giao dịch của bạn.",
    `keywords` varchar(1000) DEFAULT "TrumAcc.Vn,shop acc game",
    `copyright` varchar(1000) DEFAULT "YODY DEV",
    `email` varchar(255) NOT NULL DEFAULT "admin@gmail.com",
    `hotline` varchar(255) NOT NULL DEFAULT "0123456789",
    `Fanpage` varchar(255) NOT NULL,
    `group` varchar(255) NOT NULL,
    `messenger` varchar(255) NOT NULL,    
    `phirut` varchar(255) NOT NULL DEFAULT 1,
    PRIMARY KEY (`id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `setting` SET `email` = 'admin@gmail.com';


CREATE TABLE IF NOT EXISTS `promo` (
    `id` int(11) NOT NULL AUTO_INCREMENT,   
    `ShopID` varchar(255) NOT NULL,
    `code` text NOT NULL,
    `count` text NOT NULL,
    `usemin` varchar(255) NOT NULL,
    `type` varchar(255) NOT NULL,
    `quantity` text NOT NULL,
    `game` varchar(255) NOT NULL,
    `used` varchar(255) NOT NULL DEFAULT 0,
    `createDate` varchar(255) NOT NULL,
    PRIMARY KEY (`id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `buy` (
    `id` int(11) NOT NULL AUTO_INCREMENT,   
    `UserID` varchar(255) NOT NULL,
    `ShopID` varchar(255) NOT NULL,
    `MTK` varchar(255) NOT NULL,
    `account` varchar(255) NOT NULL,
    `password` varchar(255) NOT NULL,
    `login` varchar(255) NOT NULL,
    `price` varchar(255) NOT NULL,
    `game` varchar(255) NOT NULL,
    `note` text NOT NULL ,
    `promo_code` varchar(255),
    `info` varchar(255) NOT NULL,
    `rating` varchar(255) NOT NULL,
    `createDate` varchar(255) NOT NULL,
    PRIMARY KEY (`id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `withdraw` (
    `id` int(11) NOT NULL AUTO_INCREMENT,   
    `ShopID` varchar(255) NOT NULL,
    `stk` text NOT NULL ,
    `ctk` text NOT NULL,
    `bank` text NOT NULL,
    `quantity` text NOT NULL,
    `thucnhan` varchar(255) NOT NULL,
    `nhan` varchar(255) NOT NULL,
    `status` varchar(255) NOT NULL,
    `createDate` varchar(255) NOT NULL,
    PRIMARY KEY (`id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `post` (
    `id` int(11) NOT NULL AUTO_INCREMENT,   
    `UserID` varchar(255) NOT NULL,
    `content` text NOT NULL ,
    `comment` varchar(5000) NOT NULL DEFAULT 0,
    `like` varchar(5000) NOT NULL DEFAULT 0,
    `cmtType` varchar(255) NOT NULL,
    `likeType` varchar(255) NOT NULL,
    `status` varchar(255) NOT NULL,
    `createDate` varchar(255) NOT NULL,
    PRIMARY KEY (`id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


CREATE TABLE IF NOT EXISTS `rating` (
    `id` int(11) NOT NULL AUTO_INCREMENT,   
    `UserID` varchar(255) NOT NULL,
    `ShopID` varchar(255) NOT NULL,
    `AccID` varchar(255) NOT NULL,
    `content` text NOT NULL ,
    `star` varchar(255) NOT NULL,
    `createDate` varchar(255) NOT NULL,
    PRIMARY KEY (`id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `bank` (
    `id` int(11) NOT NULL AUTO_INCREMENT,   
    `bank` varchar(255) NOT NULL,
    `qrcode` varchar(255) NOT NULL,
    `stk` varchar(255) NOT NULL,
    `ctk` text NOT NULL ,
    `status` varchar(255) NOT NULL,
    `createDate` varchar(255) NOT NULL,
    PRIMARY KEY (`id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `card` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `UserID` varchar(255) NOT NULL,  
    `tranid` varchar(255) NOT NULL,
    `pin` varchar(255) NOT NULL,
    `serial` varchar(255) NOT NULL,
    `telco` varchar(255) NOT NULL ,
    `amount` varchar(255) NOT NULL ,
    `note` text NOT NULL ,
    `status` varchar(255) NOT NULL,
    `createDate` varchar(255) NOT NULL,
    PRIMARY KEY (`id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

