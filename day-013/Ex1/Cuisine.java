package Ex1;

public class Cuisine {
    public static void main ( String [] args ) {
        Ingredient tomate = new Ingredient( " Tomate " , 18);
        Ingredient mozzarella = new Ingredient( " Mozzarella " , 280);
        Plat tomoza = new Plat(" Tomate - Mozzarella ");
        // tomoza . nom = " Tomate - Mozzarella " ;
        tomoza . getIngredients() . put ( tomate , 300);
        tomoza . getIngredients(). put ( mozzarella , 200);
        System . out . print ( " Le plat " + tomoza . getNom() + " se compose de : " );
        for ( Ingredient i : tomoza . getIngredients() . keySet ())
            System . out . print ( i .getNom() + " " );
        int calories = 0;
        for ( Ingredient i : tomoza . getIngredients() . keySet ())
            calories += i . getCalories() * tomoza . getIngredients() . get ( i ) / 100;
        System . out . println ( " \n"+"Sa valeur calorique est : " + calories );
    }
}
