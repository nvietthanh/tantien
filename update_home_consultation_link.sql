-- ==============================================================================
-- Migration SQL: Update nút "Nhận tư vấn" trên Banner Trang Chủ sang /lien-he/
-- Áp dụng cho: Môi trường Development & Production
-- ==============================================================================

UPDATE `wp_posts` 
SET `post_content` = REPLACE(
    `post_content`, 
    '[ttw_hero_button text="Nhận tư vấn" link="/bao-gia/" type="light"]', 
    '[ttw_hero_button text="Nhận tư vấn" link="/lien-he/" type="light"]'
)
WHERE `post_type` = 'page' 
  AND (`ID` = 30 OR `post_name` = 'trang-chu');
