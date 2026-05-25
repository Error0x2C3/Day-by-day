<?php

class Item_categories extends Model {
    private int $item_id;
    private int $categorie_id;
    public function __construct(int $item_id,int $categorie_id){
        $this->item_id = $item_id;
        $this->categorie_id = $categorie_id;
    }

    public function get_categorie_id(): int{
        return $this->categorie_id;
    }

    public function set_categorie_id(int $categorie_id): void{
        $this->categorie_id = $categorie_id;
    }

    public function get_item_id(): int{
        return $this->item_id;
    }

    public function set_item_id(int $item_id): void{
        $this->item_id = $item_id;
    }

    public static function get_items_category_by_category(int $category){
        try {
            $pdo = self::execute(
                "SELECT * FROM item_categories WHERE category=:category",
                array(
                    "category"=> $category,
                ));
            if (!$pdo){ return false;};
            $rows = $pdo->fetchAll(PDO::FETCH_ASSOC);
            $tab = [];
            foreach ($rows as $row) {
                $tab[] = new item_categories(
                    item_id:(int)$row['item'],
                    categorie_id: (int)$row['category']
                );
            }
            return $tab;
        } catch (PDOException $e) {
            return false;
        }
    }
}