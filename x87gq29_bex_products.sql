-- phpMyAdmin SQL Dump
-- version 4.7.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 14, 2017 at 04:24 PM
-- Server version: 5.6.10
-- PHP Version: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bex-dev`
--

-- --------------------------------------------------------

--
-- Table structure for table `x87gq29_bex_products`
--

CREATE TABLE `x87gq29_bex_products` (
  `id` int(3) UNSIGNED NOT NULL,
  `brands_id` tinyint(4) NOT NULL,
  `product_name` varchar(70) NOT NULL,
  `product_url` text NOT NULL,
  `product_image_width` smallint(5) UNSIGNED NOT NULL,
  `product_image_height` smallint(5) UNSIGNED NOT NULL,
  `product_image` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `ingredients` text NOT NULL,
  `toxic` tinyint(1) NOT NULL DEFAULT '0',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `modified_by` int(10) UNSIGNED NOT NULL,
  `modified_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `x87gq29_bex_products`
--

INSERT INTO `x87gq29_bex_products` (`id`, `brands_id`, `product_name`, `product_url`, `product_image_width`, `product_image_height`, `product_image`, `description`, `ingredients`, `toxic`, `published`, `modified_by`, `modified_on`) VALUES
(5, 1, 'Grapefruit Citrus Nutrient Oil', '', 0, 0, '15513955994079PyC6.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In egestas neque a pretium aliquam. In a egestas mauris, eget ullamcorper lectus. Morbi justo lacus, malesuada ut scelerisque pretium, lobortis non eros. Donec aliquam sapien vitae euismod iaculis. Quisque aliquet, purus sit amet vulputate aliquet, nisi lectus vestibulum orci, in cursus turpis lacus ac ante. Etiam cursus quis turpis quis dignissim. In in libero sed eros pellentesque varius. Interdum et malesuada fames ac ante ipsum primis in faucibus. Suspendisse fermentum ut lacus in posuere. Maecenas a tortor justo.', 'Simmondsia Chinensis (Jojoba) Oil,  Carthamus Tinctorius (Safflower) Seed Oil,  Prunus Armeniaca (Apricot) Kernel Oil,  Persea Gratissima (Avocado) Oil,  Olea Europaea (Olive) Oil,  Citrus Grandis (Grapefruit) Peel Oil,  Citrus Aurantium (Orange) Peel Oil', 1, 1, 387, '2017-08-08 12:43:11'),
(6, 1, 'Rosehip Cleanser', '', 0, 0, '157314485407RbsjOD.jpg', '<span xss=removed>Harnessing the power of vitamin-rich rosehip oil, this gentle yet indulgent cleanser and eye makeup remover soothes skin to help slow the signs of aging, preserve moisture, and treat redness and rosacea.</span>', 'Aqua,   Sodium Olivate (Fatty Acid From Olive),   Glycerin,   Rosa Rubiginosa Seed Oil,   Chondrus Crispus,   Eriobotrya Japonica Extract', 1, 1, 387, '2017-08-08 12:46:49'),
(7, 1, 'Blemish Stick', 'https://www.dermstore.com/product_ELASTIderm+Eye+Treatment+Cream_9346.htm', 0, 0, '1557869634YRdC1ujq.jpg', 'Formally known as Night Eye Cream, Obagi ELASTIderm Eye Treatment Cream is a firming formula that strengthens skin by encouraging the production of collagen and elastin. The advanced blend of ingredients makes use of the body\'s natural skin renewal processes to achieve results in less time than traditional eye creams. Ideal for mature or damaged skin, it helps restore a youthful glow and healthy appearance to the entire eye area.', 'Water, Ethylhexyl Palmitate, C13-15 Alkane, Glycerin, Glyceryl Stearate, Cyclopentasiloxane, C12-15 Alkyl Benzoate, Peg-100 Stearate, Diproprylene Glycol Dibenzoate, Propylene Glycol, Dimethicone, Stearyl Alcohol, PPG-15 Stearyl Ether Benzoate, Polyacrylamide, Cetyl Alcohol, Cetyl Dimethicone, C13-14 Isoparaffin, Xanthan Gum, Magnesium Aluminum Silicate, Laureth-7, Tocopheryl Acetate (Vitamin E Acetate), Glycyrrhiza Glabra (Licorice) Root Extract, Algae Extract, Vaccinium Angustifolium (Blueberry) Fruit Extract, Phenoxyethanol, Methylparaben, Ethylparaben, Butylparaben, Propylparaben, Isobutylparaben, HDI/Trimethylol Hexyllactone Crosspolymer, Sodium Hydroxide, Malonic Acid, Malachite, Zinc Carbonate, Silica, Talc, Alumina, Titanium Dioxide (CI 77891), Mica (CI 77019), Iron Oxides (CI 77491).', 1, 1, 388, '2017-08-08 20:58:17'),
(8, 1, 'CO-Q 10 Toner', '', 0, 0, '1572859101Zx3mJCYu.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In egestas neque a pretium aliquam. In a egestas mauris, eget ullamcorper lectus. Morbi justo lacus, malesuada ut scelerisque pretium, lobortis non eros. Donec aliquam sapien vitae euismod iaculis. Quisque aliquet, purus sit amet vulputate aliquet, nisi lectus vestibulum orci, in cursus turpis lacus ac ante. Etiam cursus quis turpis quis dignissim. In in libero sed eros pellentesque varius. Interdum et malesuada fames ac ante ipsum primis in faucibus. Suspendisse fermentum ut lacus in posuere. Maecenas a tortor justo.', 'Purified Water (Aqua),  Aloe Barbadensis,  Coenzyme Q10,  Hyaluronic Acid,  Anthemis Nobilis (Chamomile),  Salvia Officinalis (Sage),  Carica Papaya Leaf Extract,  Cucums Sativus (Cucumber) Extract,  Citrus Aurantium Flower Oil,  Phenoxyethanol And Ethylhexylglycerin', 1, 1, 388, '2017-08-08 11:48:25'),
(9, 0, 'Swiss Apple Face Serum', '', 0, 0, '1551630345q0YHWcbr.jpg', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In egestas neque a pretium aliquam. In a egestas mauris, eget ullamcorper lectus. Morbi justo lacus, malesuada ut scelerisque pretium, lobortis non eros. Donec aliquam sapien vitae euismod iaculis. Quisque aliquet, purus sit amet vulputate aliquet, nisi lectus vestibulum orci, in cursus turpis lacus ac ante. Etiam cursus quis turpis quis dignissim. In in libero sed eros pellentesque varius. Interdum et malesuada fames ac ante ipsum primis in faucibus. Suspendisse fermentum ut lacus in posuere. Maecenas a tortor justo.', 'Seaweed Extract,  Purified Water,  Hyaluronic Acid,  Malus Domestica Fruit Cell Culture,  Xanthan Gum,  Lecithin,  Phenoxyethanol,  Ethylhexylglycerin', 1, 1, 388, '2017-07-16 17:11:54'),
(10, 2, 'Treatment Cleansing Foam', '', 0, 0, '1524276910MnHrEZRJ.jpg', '<p>Treatment Cleansing Foam</p>', 'Water,   Glycerin,   Stearic Acid,   Myristic Acid,   Potassium Hydroxide,   Lauric Acid,   Peg-8,   Sodium Myristoyl Glutamate,   Glyceryl Stearate,   Peg-32,   Peg/Ppg-25/30 Copolymer,   Sorbitol,   Peg-200 Hydrogenated Glyceryl Palmate,   Cocamidopropyl Betaine,   Phyllostachis Bambusoides Juice,   Panax Ginseng Root Extract,   Mangifera Indica (Mango) Seed Butter,   Olea Europaea (Olive) Fruit Oil,   Helianthus Annuus (Sunflower) Seed Oil,   Algae Extract,   Glycyrrhiza Glabra (Licorice) Root Extract,   Corallina Officinalis Extract,   Lactose,   Disodium Edta,   Microcrystalline Cellulose,   Butylene Glycol,   Sucrose,   Alcohol,   Zea Mays (Corn) Starch,   Olive Oil Peg-8 Esters,   Ultramarines (Ci 77007),   Xanthan Gum,   Tocopheryl Acetate,   Trehalose,   Triclosan,   Phosphatidylcholine,   Propylene Glycol,   Peg-100 Stearate,   Peg-14M,   Peg-5 Rapeseed Sterol,   Peg-7 Glyceryl Cocoate,   Hydrogenated Lecithin,   Sodium Benzoate,   Fragrance', 0, 1, 387, '2017-08-08 12:47:14'),
(11, 2, 'MOISTURE BOUND Sleeping Recovery Masque', '', 0, 0, '15655946472TQ7oDeI.jpg', '<p>MOISTURE BOUND Sleeping Recovery Masque<br></p>', 'Phyllostachis Bambusoides Juice,   Panax Ginseng Root Extract,   Butylene Glycol,   Alcohol,   Glycerin,   Dimethicone,   Squalane,   Cyclopentasiloxane,   Glycereth-26,   Water,   Camellia Sinensis Leaf Extract,   Cyclohexasiloxane,   Hydrolyzed Camellia Sinensis Leaf,   Hydrolyzed Phyllostachis Bambusoides,   Nelumbo Nucifera Flower Extract,   Opuntia Coccinellifera Fruit Extract,   Glycine Soja (Soybean) Seed Extract,   Magnesium Aspartate,   Zinc Gluconate,   Copper Gluconate,   Calcium Gluconate,   Zingiber Officinale (Ginger) Root Extract,   Tricholoma Matsutake Extract,   Daucus Carota Sativa (Carrot) Root Extract,   Camellia Sinensis Flower Extract,   Lactobacillus/Water Hyacinth Ferment,   Theanine,   Epigallocatechin Gallate,   Acetyl Glucosamine,   Sodium Hyaluronate,   Tocopherol,   Kaempferol,   Betaine,   Beta-Glucan,   Niacinamide,   Natto Gum,   Peg-60 Hydrogenated Castor Oil,   Polysilicone-11,   Acrylates/C10-30 Alkyl Acrylate Crosspolymer,   Dimethiconol,   Ammonium Acryloyldimethyltaurate/Vp Copolymer,   Hydroxyethylcellulose,   Propanediol,   Xanthan Gum,   Dipotassium Glycyrrhizate,   Stearyl Behenate,   Polyglyceryl-3 Methylglucose Distearate,   Peg-15 Pentaerythrityl Tetra(Laureth-6 Carboxylate),   Caffeine,   Mannitol,   Phytantriol,   Hydroxypropyl Bispalmitamide Mea,   Poloxamer 407,   Inulin Lauryl Carbamate,   Polysorbate 20,   Ethylhexylglycerin,   Potassium Hydroxide,   Disodium Edta,   Phenoxyethanol,   Fragrance', 0, 1, 387, '2017-08-08 12:45:09'),
(12, 3, 'Good Genes All-In-One Lactic Acid Treatment', '', 0, 0, '1543368366EsLGRz4V.jpg', 'Good Genes All-In-One Lactic Acid Treatment', 'Opuntia Tuna Fruit (Prickly Pear) Extract,   Agave Tequilana Leaf (Blue Agave) Extract,   Cypripedium Pubescens (Lady&amp;#039;S Slipper Orchid) Extract,   Opuntia Vulgaris (Cactus) Extract,   Aloe Barbadensis Leaf Extract &amp;amp; Saccharomyses Cerevisiae (Yeast) Extract,   Lactic Acid,   Caprylic/Capric Triglyceride,   Butylene Glycol,   Squalane,   Cyclomethicone,   Dimethicone,   Ppg-12/Smdi Copolymer,   Stearic Acid,   Cetearyl Alcohol And Ceteareth20,   Glyceryl Stearate And Peg-100 Stearate,   Arnica Montana (Flower) Extract,   Peg-75 Meadowfoam Oil,   Glycyrrhiza Glabra (Licorice) Root Extract,   Cymbopogon Schoenanthus (Lemongrass) Oil,   Triethanolamine,   Xantham Gum,   Phenoxyethanol,   Steareth-20,   Dmdm Hydantoin.', 0, 1, 387, '2017-08-08 12:42:49'),
(13, 0, 'Tidel Brightening Enzyme Water Cream', '', 0, 0, '15106351789GYVmiDv.jpg', 'Tidel Brightening Enzyme Water Cream', 'Water, Hydrolyzed Jojoba Esters, Glycerin, Caprylic/Capric Triglyceride, Propanediol, Sodium Hyaluronate Crosspolymer, Pentylene Glycol, Tamarindus Indica (Tamarind) Seed Gum, Ethyl Macadamiate, Sodium Acrylates Copolymer (And) Lecithin, Isododecane, Adipic Acid/Neopentyl Glycol Crosspolymer, Lauryl Dimethicone, Hydrogenated Polyisobutene, Strelitzia Nicolai (Bird Of Paradise Flower) Seed Aril Extract, Alpha-Arbutin, Aesculus Hippocastanum (Horse Chestnut) Seed Extract, Hydrolyzed Hyaluronic Acid, Allantoin, Papain And Carbome 1, 2-Hexanediol (And) Caprylyl Glycol (And) Algin, Cucumis Sativus (Cucumber) Extract, Melia Azadirachta (Neem) Leaf Extract (And) Melia Azadirachta Flower (Neem) Extract (And) Amino Esters-1 (And) Coccinia Indica Fruit Extract (And) Solanum Melongena (Eggplant) Fruit Extract, Aloe Barbadensis Flower Extract (And) Lawsonia Inermis Extract (And) Ocimum Sanctum Leaf (Holy Basil) Extract, Pearl Powder, Fragrance, Synthetic Fluorphlogopite, Titanium Dioxide, Phenoxyethanol, Chlorphenesin.', 0, 1, 388, '2017-07-16 17:14:55'),
(14, 3, 'U.F.O. Ultra-Clarifying Face Oil', '', 0, 0, '1510079184lnGVZdc0.jpg', 'U.F.O. Ultra-Clarifying Face Oil', 'Silybum Marianum (Milk Thistle) Seed Oil,   Nigella Sativa (Black Cumin) Seed Oil,   Cucumis Sativus (Cucumber) Seed Oil,   Diisopropyl Sebacate,   Punica Granatum (Pomegranate) Seed Oil,   Vaccinium Macrocarpon (Cranberry) Seed Oil,   Dimethyl Isosorbide,   Linum Usitatissimum (Flax) Seed Oil,   Caprylic/Capric Triglycerides,   Ethyl Linoleate,   Hexylresorcinol,   Glycyrrhiza Glabra (Licorice) Root Extract,   Salicylic Acid,   Prunus Armeniaca (Apricot) Kernel Oil,   Chamomilla Recutita Flower Oil,   Citrus Aurantium Amara (Neroli) Flower Oil,   Melaleuca Alternifolia (Tea Tree) Leaf Oil,   Helianthus Annuus Seed Oil,   Rosmarinus Officinalis (Rosemary) Leaf Extract,   Citrus Paradisii (Grapefruit) Peel Oil,   Melia Azadirachta (Neem) Leaf Extract,   Melia Azadirachta (Neem) Flower Extract,   Coccinia Indica Fruit Extract,   Amino Esters-1,   Amber Leaf Extract,   Solanum Melongena (Eggplant) Fruit Extract,   Aloe Barbadensis Flower Extract,   Lawsonia Inermis (Henna) Extract,   Ocimum Sanctum Leaf Extract,   Ocimum Basilicum (Basil) Leaf Extract,   Curcuma Longa (Turmeric) Root Extract,   Corallina Officinalis Extract,   Simmondsia Chinensis (Jojoba) Seed Oil,   Pearl Powder,   Moringa Pterygosperma Seed Oil.', 0, 1, 387, '2017-08-08 12:47:32'),
(15, 3, 'Juno Hydroactive Cellular Face Oil', '', 0, 0, '1559125913ltaFTv0C.jpg', 'Juno Hydroactive Cellular Face Oil', 'Rubus Fruticosus (Cold Pressed Blackberry) Seed Oil,   Vaccinium Corymbosum (Cold Pressed Blueberry) Seed Oil,   Vitis Vinifera (Cold Pressed Chardonnay Grape Seed Oil) Seed Oil,   Vaccinium Macrocarpon (Cold Pressed Cranberry) Seed Oil,   Rubus Idaeus (Cold Pressed Red Raspberry) Seed Oil,   Daucus Carota Sativa (Cold Pressed Wild Carrot) Seed Oil,   Nigella Sativa (Cold Pressed Black Cumin) Seed Oil,   Brassica Oleracea Italic (Cold Pressed Broccoli) Seed Oil,   Limnanthes Alba (Meadowfoam) Seed Oil.', 0, 1, 387, '2017-08-08 12:44:22'),
(16, 4, 'Evercalm Ultra Comforting Rescue', '', 0, 0, '1529746996mUjQD9lJ.jpg', 'Evercalm Ultra Comforting Rescue', 'Water,          Glycerin,         Cetearyl Alcohol,         Caprylyl Caprylate/Caprate,         Olus Oil,         Lactobacillus Ferment,         Butyrospermum Parkii (Shea) Butter,         Helianthus Annuus (Sunflower) Seed Wax,         Simmondsia Chinensis (Jojoba) Seed Oil,         Cetearyl Glucoside,         Propanediol,         Algae Extract,         Cetyl Alcohol,         Lactobacillus,         Alpha-Glucan Oligosaccharide,         Parfum* (Fragrance),         Tocopheryl Acetate,         Caprylic/Capric Triglyceride,         Panthenol,         Carbomer,         Vaccinium Vitis-Idaea (Lingonberry) Seed Oil,         Xanthan Gum,         Arnica Montana Flower Extract,         Camelina Sativa Seed Oil,         Cocos Nucifera (Coconut) Fruit Extract,         Tocopherol,         Magnesium Carboxymethyl Beta-Glucan,         Malachite Extract,         Albatrellus Ovinus Extract,         Laminaria Ochroleuca Extract,         Glucose,         Phenoxyethanol,         Helianthus Annuus (Sunflower) Seed Oil,        Citric Acid,       Sodium Hydroxide,        Rosmarinus Officinalis Leaf Extract,         Citronellol,         Geraniol,         Limonene,         Linalool', 0, 1, 388, '2017-08-08 21:47:02'),
(17, 5, 'Pai Rosehip BioRegenerate Oil', '', 0, 0, '15572750530geSlN85.jpg', '<p>Pai Rosehip BioRegenerate Oil<br></p>', 'Rosa Canina (Rosehip Co2) Seed Extract*,   Rosa Canina (Rosehip Co2) Fruit Extract*,   Mixed Tocopherols (Natural Vitamin E)', 1, 1, 387, '2017-08-08 12:45:56'),
(18, 5, 'Instant Calm Redness Serum Sea Aster &amp;amp; Wild Oat', '', 0, 0, '1561788794hXFZiM16.jpg', '<p>Instant Calm Redness Serum Sea Aster & Wild Oat<br></p>', 'Aqua,   Citrus Aurantium Dulcis Fruit Water*,   Avena Sativa Kernel Extract*,   Glycerin*,   Glyceryl Stearate Citrate,   Cetearyl Alcohol,   Aster Maritima/ Tripolium Extract,   Sodium Hyaluronate,   Carya Ovata Bark Extract,   Litsea Cubeba Fruit Oil*,   Adesmia Boronoides Flower/ Leaf/Stem Oil*,   Helianthus Annuus Seed Oil*,   Caprylic/Capric Triglyceride,   Acacia Senegal Gum,   Xantham Gum,   Sodium Levulinate,   Sodium Anisate,   Glyceryl Caprylate,   Tocopherol,   Lactic Acid,   Rosmarinus Officinalis Leaf Extract*,   Citral**,   Limonene**', 1, 1, 387, '2017-08-08 12:44:04'),
(19, 5, 'Kikui &amp;amp; Jojoba Bead Skin Brightening Exfoliator', '', 0, 0, '15811413723FwmzuXg.jpg', '<p>Kikui & Jojoba Bead Skin Brightening Exfoliator <br></p>', 'Kukui Nut Oil,      Natural Jojoba Beads,      Helianthus Annuus Seed Oil*,      Glycerin*,     Prunusamygdalusdulcis Oil* ,      Aleuritesmoluccana Seed Oil*,      Prunusarmeniaca Kernel Oil*,      Carthamustinctorius Seed Oil*,      Perseagratissima Oil*,      Hydrogenated Castor Oil (And) Hydrogenated Jojoba Oil,      Sucrose Laurate,      Citrus Aurantiumdulcis Fruit Water*,      Boswelliacarterii Oil*,      Litseacubeba Fruit Oil*,      Mixed Tocopherols', 1, 1, 387, '2017-08-08 12:44:47'),
(20, 5, 'Perfect Balance Blemish Serum Copalba &amp;amp; Zinc', '', 0, 0, '1585168787VoDF9Pk0.jpg', '<p>Perfect Balance Blemish Serum Copalba & Zinc<br></p>', 'Aqua,   Citrus Limon Fruit Water*,   Glycerin*,   Glyceryl Stearate Citrate,   Cetearyl Alcohol,   Carya Ovata Bark Extract,   Zinc Pca,   Copaifera Officinalis Resin*,   Aleurites Moluccana Seed Oil*,   Sodium Hyaluronate,   Euterpe Oleracea Fruit Oil*,   Carapa Guaianensis Seed Oil*,   Illicum Verum Fruit Oil*,   Citrus Aurantium Dulcis Peel Oil*,   Glyceryl Caprylate,   Citrus Medica Limonum Peel Oil*,   Crithmum Maritimum Essential Oil,   Acacia Senegal Gum,   Xanthan Gum,   Sodium Levulinate,   Tocopherol,   Lactic Acid,   Sodium Anisate,   Citral**,   Limonene**,   Linalool**', 1, 1, 387, '2017-08-08 12:46:15'),
(21, 6, 'Hyaluronic Moisture Cushion', '', 0, 0, '1592380374LWqj3Qi5.jpg', '<p>Hyaluronic Moisture Cushion<br></p>', 'Water/Aqua/Eau,  Cyclopentasiloxane,  Cetearyl Alcohol,  Dimethicone,  Stearyl Dimethicone,  Butylene Glycol,  Caprylic/Capric Triglyceride,  Octadecane,  Glyceryl Behenate,  Sodium Acrylate/Sodium Acryloyldimethyl Taurate Copolymer,  Collagen,  Sodium Hyaluronate,  Aloe Barbadensis Leaf Extract,  Phospholipids,  Citrullus Lanatus (Watermelon) Fruit Extract,  Cocos Nucifera (Coconut) Fruit Extract,  Citrus Sinensis (Orange) Fruit Extract,  Cinnamomum Cassia Bark Extract,  Saccharide Isomerate,  Vaccinium Myrtillus Fruit/Leaf Extract,  Glycolic Acid,  Lactic Acid,  Saccharum Officinarum (Sugar Cane) Extract,  Citrus Aurantium Dulcis (Orange) Fruit Extract,  Citrus Limon (Lemon) Fruit Extract,  Acer Saccharum (Sugar Maple) Extract,  Hydrolyzed Soy Protein,  Acrylates/Carbamate Copolymer,  Isohexadecane,  Polysorbate 80,  Dimethicone Crosspolymer-3,  Ethylhexylglycerin,  Isododecane,  Ceteareth-20,  Potassium Cetyl Phosphate,  Phenoxyethanol,  Fragrance (Parfum),  Disodium Edta-Copper.', 0, 1, 387, '2017-08-08 12:43:39'),
(22, 6, 'Ferulic Plus Retinol Anti-Aging Moisturizer', '', 0, 0, '1543268172EGAjhYbF.jpg', '<p>Ferulic Plus Retinol Anti-Aging Moisturizer<br></p>', 'Water/Aqua/Eau,  Dimethicone,  Isododecane,  Cyclopentasiloxane,  Dimethiconol,  Cyclohexasiloxane,  Butylene Glycol,  Stearyl Dimethicone,  Caprylic/Capric Triglyceride,  Sodium Acrylate/Sodium Acryloyldimethyl Taurate Copolymer,  Potassium Cetyl Phosphate,  Octadecane,  Cetearyl Alcohol,  Polymethyl Methacrylate,  Isohexadecane,  Collagen,  Ferulic Acid,  Gallic Acid,  Ellagic Acid,  Arnica Montana Flower Extract,  Centella Asiatica Extract,  Retinol,  Glycyrrhiza Glabra (Licorice) Root Extract,  Tocopherol,  Morus Alba Leaf Extract,  Sodium Hyaluronate,  Arctostaphylos Uva Ursi Leaf Extract,  Citrus Aurantium Bergamia (Bergamot) Leaf Extract,  Citrus Aurantium Bergamia (Bergamot) Leaf Oil,  Glycolic Acid,  Lactic Acid,  Hydrolyzed Soy Protein,  Glycine Soja (Soybean) Oil,  Adenosine,  Saccharide Isomerate,  Salix Alba (Willow) Bark Extract,  Tetrapeptide-21,  Caprylyl Glycol,  Glycerin,  Glyceryl Behenate,  Acrylates/Carbamate Copolymer,  Tetrahydropiperine,  Bht,  Benzophenone-3,  Glyceryl Stearate Se,  Citric Acid,  Bis-Vinyl Dimethicone/Dimethicone Copolymer,  Dimethicone Crosspolymer-3,  Cyclodextrin,  Sodium Citrate,  Tetrasodium Edta,  Hexylene Glycol,  Polysorbate 80,  Phenoxyethanol,  Potassium Sorbate,  Sodium Benzoate,  Annatto.', 0, 1, 387, '2017-08-08 12:42:34'),
(23, 7, 'Resurfacing mask', '', 0, 0, '1547188162VCL04Ari.jpg', 'Resurfacing mask', 'Aloe Barbadensis Leaf Juice*,   Salix Alba (Willow) Bark Extract,   Sclerotium Gum,   Aqua/Water/Eau,   Lactobacillus Ferment,   Maltooligosyl Glucoside,   Lactobacillus/Punica Granatum Fruit Ferment Extract,   Leuconostoc Ferment Filtrate,   Hydrogenated Starch Hydrolysate,   Hydrolyzed Corn Starch,   Beta Vulgaris/Beet Root Extract/Extrait De Racine De Betterave,   Kaolin (Rose Clay),   Sodium Phytate,   Aroma**,   Benzyl Alcohol,   Citral,   Citronellol,   Eugenol,   Geraniol,   Limonene,   Linalool', 1, 1, 387, '2017-08-08 12:46:31'),
(24, 1, 'Hyaluronic Acid Intensifier', '', 0, 0, '1568925726DSwxHPe2.jpg', '<p>Hyaluronic Acid Intensifier<br></p>', 'Aqua / Water,   Cyclohexasiloxane,   Glycerin,   Alcohol Denat.,   Hydroxypropyl Tetrahydropyrantriol,   Propylene Glycol,   Dipotassium Glycyrrhizate,   Polysilicone-11,   Polymethylsilsesquioxane,   Sodium Hyaluronate,   Dimethicone,   Tocopherol,   Phenoxyethanol,   Capryloyl Salicylic Acid,   Octyldodecanol,   Bis-peg/ppg-16/16 Peg/ppg-16/16 Dimethicone,   Peg-20 Methyl Glucose Sesquistearate ,   Ammonium Polyacryloyldimethyl Taurate Caprylyl Glycol,   Xanthan Gum,   Dextrin,   Oryza Sativa Extract / Rice Extract,   Disodium Edta,   Caprylic/capric Triglyceride,   Sodium Hydroxide Adenosine,   Citrus Nobilis Peel Oil / Mandarin Orange Peel Oil,   Limonene,   T-butyl Alcohol,   Cellulose Acetate Butyrate,   Polyphosphorylcholine Glycol Acrylate,   Polyvinyl Alcohol,   Sodium Chloride,   Butylene Glycol,   Pentaerythrityl Tetra-di-t-butyl Hydroxyhydrocinnamate.', 0, 1, 387, '2017-08-08 12:43:25'),
(26, 1, 'abcd', '', 0, 0, '', 'Lavender Clarifying clarity Barley1', 'Isopropyl Alcohol,   Purified Water,   Zinc,  Sulphate,  Salicylic Acid,Glycolic Acid,Camphor, Witch Hazel, Cellulose Gum, Menthol', 0, 1, 388, '2017-08-09 09:44:59'),
(27, 2, 'Pramod Product', '', 0, 0, '', 'Pramod Product', 'Cucumber, Glycerin, Zinc, Barley, Honey, Enzymes, Avocado, Coconut Oil', 0, 1, 388, '2017-08-14 18:05:59');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `x87gq29_bex_products`
--
ALTER TABLE `x87gq29_bex_products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_name` (`product_name`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `x87gq29_bex_products`
--
ALTER TABLE `x87gq29_bex_products`
  MODIFY `id` int(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
