--
-- Création de la structure de la base de donnée
--

CREATE TABLE IF NOT EXISTS `car_accounts` (
  `accountId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `accountPseudo` varchar(50) NOT NULL,
  `accountPassword` varchar(255) NOT NULL,
  `accountEmail` varchar(50) NOT NULL,
  `accountAccess` int(11) NOT NULL,
  `accountStatus` int(11) NOT NULL,
  `accountReason` varchar(100) NOT NULL,
  `accountLastAction` datetime NOT NULL,
  `accountLastConnection` datetime NOT NULL,
  `accountLastIp` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_battles` (
  `battleId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `battleCharacterId` int(11) NOT NULL,
  `battleOpponentId` int(11) NOT NULL,
  `battleType` varchar(30) NOT NULL,
  `battleOpponentHpRemaining` int(11) NOT NULL,
  `battleOpponentMpRemaining` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_bestiary` (
  `bestiaryId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `bestiaryCharacterId` int(11) NOT NULL,
  `bestiaryMonsterId` int(11) NOT NULL,
  `bestiaryMonsterQuantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_chapters` 
(
  `chapterId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `chapterMonsterId` int(5) NOT NULL,
  `chapterTitle` varchar(30) NOT NULL,
  `chapterOpening` text NOT NULL,
  `chapterEnding` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_characters` (
  `characterId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `characterAccountId` int(11) NOT NULL,
  `characterGuildId` int(11) NOT NULL,
  `characterRaceId` int(11) NOT NULL,
  `characterplaceId` int(11) NOT NULL,
  `characterPicture` varchar(50) NOT NULL,
  `characterName` varchar(30) NOT NULL,
  `characterLevel` int(11) NOT NULL,
  `characterSex` int(11) NOT NULL,
  `characterHpMin` int(11) NOT NULL,
  `characterHpMax` int(11) NOT NULL,
  `characterHpSkillPoints` int(11) NOT NULL,
  `characterHpBonus` int(11) NOT NULL,
  `characterHpEquipments` int(11) NOT NULL,
  `characterHpGuild` int(11) NOT NULL,
  `characterHpTotal` int(11) NOT NULL,
  `characterMpMin` int(11) NOT NULL,
  `characterMpMax` int(11) NOT NULL,
  `characterMpSkillPoints` int(11) NOT NULL,
  `characterMpBonus` int(11) NOT NULL,
  `characterMpEquipments` int(11) NOT NULL,
  `characterMpGuild` int(11) NOT NULL,
  `characterMpTotal` int(11) NOT NULL,
  `characterStrength` int(11) NOT NULL,
  `characterStrengthSkillPoints` int(11) NOT NULL,
  `characterStrengthBonus` int(11) NOT NULL,
  `characterStrengthEquipments` int(11) NOT NULL,
  `characterStrengthGuild` int(11) NOT NULL,
  `characterStrengthTotal` int(11) NOT NULL,
  `characterMagic` int(11) NOT NULL,
  `characterMagicSkillPoints` int(11) NOT NULL,
  `characterMagicBonus` int(11) NOT NULL,
  `characterMagicEquipments` int(11) NOT NULL,
  `characterMagicGuild` int(11) NOT NULL,
  `characterMagicTotal` int(11) NOT NULL,
  `characterAgility` int(11) NOT NULL,
  `characterAgilitySkillPoints` int(11) NOT NULL,
  `characterAgilityBonus` int(11) NOT NULL,
  `characterAgilityEquipments` int(11) NOT NULL,
  `characterAgilityGuild` int(11) NOT NULL,
  `characterAgilityTotal` int(11) NOT NULL,
  `characterDefense` int(11) NOT NULL,
  `characterDefenseSkillPoints` int(11) NOT NULL,
  `characterDefenseBonus` int(11) NOT NULL,
  `characterDefenseEquipments` int(11) NOT NULL,
  `characterDefenseGuild` int(11) NOT NULL,
  `characterDefenseTotal` int(11) NOT NULL,
  `characterDefenseMagic` int(11) NOT NULL,
  `characterDefenseMagicSkillPoints` int(11) NOT NULL,
  `characterDefenseMagicBonus` int(11) NOT NULL,
  `characterDefenseMagicEquipments` int(11) NOT NULL,
  `characterDefenseMagicGuild` int(11) NOT NULL,
  `characterDefenseMagicTotal` int(11) NOT NULL,
  `characterWisdom` int(11) NOT NULL,
  `characterWisdomSkillPoints` int(11) NOT NULL,
  `characterWisdomBonus` int(11) NOT NULL,
  `characterWisdomEquipments` int(11) NOT NULL,
  `characterWisdomGuild` int(11) NOT NULL,
  `characterWisdomTotal` int(11) NOT NULL,
  `characterProspecting` int(11) NOT NULL,
  `characterProspectingSkillPoints` int(11) NOT NULL,
  `characterProspectingBonus` int(11) NOT NULL,
  `characterProspectingEquipments` int(11) NOT NULL,
  `characterProspectingGuild` int(11) NOT NULL,
  `characterProspectingTotal` int(11) NOT NULL,
  `characterArenaDefeate` int(11) NOT NULL,
  `characterArenaVictory` int(11) NOT NULL,
  `characterExperience` int(11) NOT NULL,
  `characterExperienceTotal` int(11) NOT NULL,
  `characterSkillPoints` int(11) NOT NULL,
  `characterGold` int(11) NOT NULL,
  `characterChapter` int(11) NOT NULL,
  `characterOnBattle` int(11) NOT NULL,
  `characterEnable` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_chat` 
(
  `chatMessageId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `chatCharacterId` int(5) NOT NULL,
  `chatDateTime` datetime NOT NULL,
  `chatMessage` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_codes` (
  `codeId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `codeName` date NOT NULL,
  `codeBegins` date NOT NULL,
  `codeFinish` date NOT NULL,
  `codeAmount` int(11) NOT NULL,
  `codeAmountRemaining` int(11) NOT NULL,
  `codeType` int(11) NOT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_codes_gift` (
  `codeGiftId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `codeGiftCodeId` int(11) NOT NULL,
  `codeGiftMoney` int(11) NOT NULL,
  `codeGiftGold` int(11) NOT NULL,
  `codeGiftcharacterLevel` int(11) NOT NULL,
  `codeGiftcharacterSex` int(11) NOT NULL,
  `codeGiftcharacterSize` double NOT NULL,
  `codeGiftcharacterWeight` double NOT NULL,
  `codeGiftcharacterMoral` double NOT NULL,
  `codeGiftcharacterThirst` double NOT NULL,
  `codeGiftcharacterHunger` double NOT NULL,
  `codeGiftcharacterPee` double NOT NULL,
  `codeGiftcharacterPooh` double NOT NULL,
  `codeGiftcharacterThrowUp` double NOT NULL,
  `codeGiftcharacterHygiene` double NOT NULL,
  `codeGiftcharacterTired` double NOT NULL,
  `codeGiftcharacterFun` double NOT NULL,
  `codeGiftcharacterAffection` double NOT NULL,
  `codeGiftcharacterHappiness` double NOT NULL,
  `codeGiftcharacterKarma` double NOT NULL,
  `codeGiftcharacterHp` int(11) NOT NULL,
  `codeGiftcharacterMp` int(11) NOT NULL,
  `codeGiftcharacterStrength` int(11) NOT NULL,
  `codeGiftcharacterMagic` int(11) NOT NULL,
  `codeGiftcharacterAgility` int(11) NOT NULL,
  `codeGiftcharacterDefense` int(11) NOT NULL,
  `codeGiftcharacterDefenseMagic` int(11) NOT NULL,
  `codeGiftcharacterWisdom` int(11) NOT NULL,
  `codeGiftitemId` int(11) NOT NULL,
  `codeGiftBonusId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_codes_used` (
  `codeUsedId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `codeUsedCodeId` int(11) NOT NULL,
  `codeUsedaccountId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_configuration` 
(
  `configurationId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `configurationGameName` varchar(30) NOT NULL,
  `configurationPresentation` text NOT NULL,
  `configurationMaxLevel` int(11) NOT NULL,
  `configurationExperience` int(11) NOT NULL,
  `configurationSkillPoint` int(11) NOT NULL,
  `configurationExperienceBonus` int(11) NOT NULL,
  `configurationGoldBonus` int(11) NOT NULL,
  `configurationDropBonus` int(11) NOT NULL,
  `configurationAccess` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_guilds` 
(
  `guildId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `guildName` varchar(30) NOT NULL,
  `guildDescription` text NOT NULL,
  `guildLevel` text NOT NULL,
  `guildHpBonus` int(11) NOT NULL,
  `guildMpBonus` int(11) NOT NULL,
  `guildStrengthBonus` int(11) NOT NULL,
  `guildMagicBonus` int(11) NOT NULL,
  `guildAgilityBonus` int(11) NOT NULL,
  `guildDefenseBonus` int(11) NOT NULL,
  `guildDefenseMagicBonus` int(11) NOT NULL,
  `guildWisdomBonus` int(11) NOT NULL,
  `guildProspectingBonus` int(11) NOT NULL,
  `guildExperience` int(11) NOT NULL,
  `guildExperienceTotal` int(11) NOT NULL,
  `guildSkillPoinst` int(11) NOT NULL,
  `guildCreationDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_guilds_members` 
(
  `guildMemberId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `guildMemberCharacterId` int(11) NOT NULL,
  `guildMemberCharacterAccess` text NOT NULL,
  `guildMemberCharacterExperience` text NOT NULL,
  `guildMemberCharacterGold` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_guilds_requests` 
(
  `guildRequestId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `guildRequestGuildId` int(11) NOT NULL,
  `guildRequestCharacterId` int(11) NOT NULL,
  `guildRequestMessage` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_inventory` 
(
  `inventoryId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `inventoryCharacterId` int(5) NOT NULL,
  `inventoryItemId` int(5) NOT NULL,
  `inventoryQuantity` int(5) NOT NULL,
  `inventoryEquipped` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_items` (
  `itemId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `itemItemTypeId` int(11) NOT NULL,
  `itemRaceId` int(11) NOT NULL,
  `itemPicture` text NOT NULL,
  `itemName` varchar(30) NOT NULL,
  `itemDescription` text NOT NULL,
  `itemLevel` int(11) NOT NULL,
  `itemLevelRequired` int(11) NOT NULL,
  `itemHpEffect` int(11) NOT NULL,
  `itemMpEffect` int(11) NOT NULL,
  `itemStrengthEffect` int(11) NOT NULL,
  `itemMagicEffect` int(11) NOT NULL,
  `itemAgilityEffect` int(11) NOT NULL,
  `itemDefenseEffect` int(11) NOT NULL,
  `itemDefenseMagicEffect` int(11) NOT NULL,
  `itemWisdomEffect` int(11) NOT NULL,
  `itemProspectingEffect` int(11) NOT NULL,
  `itemPurchasePrice` int(11) NOT NULL,
  `itemSalePrice` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_items_types` (
  `itemTypeId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `itemTypeName` varchar(30) NOT NULL,
  `itemTypeNameShow` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_market` (
  `marketId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `marketCharacterId` int(11) NOT NULL,
  `marketItemId` int(11) NOT NULL,
  `marketSalePrice` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_monsters` (
  `monsterId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `monsterPicture` varchar(50) NOT NULL,
  `monsterName` varchar(30) NOT NULL,
  `monsterDescription` text NOT NULL,
  `monsterLevel` int(11) NOT NULL,
  `monsterHp` int(11) NOT NULL,
  `monsterMp` int(11) NOT NULL,
  `monsterStrength` int(11) NOT NULL,
  `monsterMagic` int(11) NOT NULL,
  `monsterAgility` int(11) NOT NULL,
  `monsterDefense` int(11) NOT NULL,
  `monsterDefenseMagic` int(11) NOT NULL,
  `monsterExperience` int(11) NOT NULL,
  `monsterGold` int(11) NOT NULL,
  `monsterLimited` varchar(30) NOT NULL,
  `monsterQuantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_monsters_drops`
(
  `monsterDropID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `monsterDropMonsterId` int(11) NOT NULL,
  `monsterDropItemId` int(11) NOT NULL,
  `monsterDropItemVisible` varchar(3),
  `monsterDropRate` int(11) NOT NULL,
  `monsterDropRateVisible` varchar(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_news` 
(
  `newsId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `newsPicture` varchar(50) NOT NULL,
  `newsTitle` varchar(30) NOT NULL,
  `newsMessage` text NOT NULL,
  `newsAccountPseudo` varchar(15) NOT NULL,
  `newsDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_notifications` 
(
  `notificationId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `notificationCharacterId` int(11) NOT NULL,
  `notificationDateTime` datetime NOT NULL,
  `notificationMessage` text NOT NULL,
  `notificationRead` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_private_conversation` 
(
  `privateConversationId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `privateConversationCharacterOneId` int(5) NOT NULL,
  `privateConversationCharacterTwoId` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_private_conversation_message` 
(
  `privateConversationMessageId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `privateConversationMessagePrivateConversationId` int(11) NOT NULL,
  `privateConversationMessageCharacterId` int(11) NOT NULL,
  `privateConversationMessageDateTime` datetime NOT NULL,
  `privateConversationMessage` text NOT NULL,
  `privateConversationMessageRead` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_races` 
(
  `raceId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `racePicture` varchar(50) NOT NULL,
  `raceName` varchar(30) NOT NULL,
  `raceDescription` text NOT NULL,
  `raceHpBonus` int(11) NOT NULL,
  `raceMpBonus` int(11) NOT NULL,
  `raceStrengthBonus` int(11) NOT NULL,
  `raceMagicBonus` int(11) NOT NULL,
  `raceAgilityBonus` int(11) NOT NULL,
  `raceDefenseBonus` int(11) NOT NULL,
  `raceDefenseMagicBonus` int(11) NOT NULL,
  `raceWisdomBonus` int(11) NOT NULL,
  `raceProspectingBonus` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_shops` 
(
  `shopId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `shopPicture` varchar(50) NOT NULL,
  `shopName` varchar(30) NOT NULL,
  `shopDescription` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_shops_items` 
(
  `shopItemId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `shopItemShopId` varchar(30) NOT NULL,
  `shopItemItemId` varchar(30) NOT NULL,
  `shopItemDiscount` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_places` 
(
  `placeId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `placePicture` varchar(50) NOT NULL,
  `placeName` varchar(30) NOT NULL,
  `placeDescription` text NOT NULL,
  `placePriceInn` int(10) NOT NULL,
  `placeChapter` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_places_monsters` 
(
  `placeMonsterId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `placeMonsterPlaceId` int(10) NOT NULL,
  `placeMonsterMonsterId` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_places_shops` 
(
  `placeShopId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `placeShopplaceId` int(10) NOT NULL,
  `placeShopShopId` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_trades` 
(
  `tradeId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `tradeCharacterOneId` int(5) NOT NULL,
  `tradeCharacterTwoId` int(5) NOT NULL,
  `tradeMessage` varchar(30) NOT NULL,
  `tradeLastUpdate` datetime NOT NULL,
  `tradeCharacterOneTradeAccepted` varchar(30) NOT NULL,
  `tradeCharacterTwoTradeAccepted` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_trades_items` 
(
  `tradeItemId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `tradeItemTradeId` int(5) NOT NULL,
  `tradeItemItemId` int(5) NOT NULL,
  `tradeItemCharacterId` int(5) NOT NULL,
  `tradeItemItemQuantity` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_trades_golds` 
(
  `tradeGoldId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `tradeGoldTradeId` int(5) NOT NULL,
  `tradeGoldCharacterId` int(5) NOT NULL,
  `tradeGoldQuantity` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_trades_requests` 
(
  `tradeRequestId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `tradeRequestCharacterOneId` int(5) NOT NULL,
  `tradeRequestCharacterTwoId` int(5) NOT NULL,
  `tradeRequestMessage` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;