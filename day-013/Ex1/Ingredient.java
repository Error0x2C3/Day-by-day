package Ex1;

public class Ingredient {
    private final String nom ;
    private final int calories ;

    Ingredient (String nom , int calories ) {
        this . nom = nom ;
        if(calories < 0){
            throw  new RuntimeException("Les calories ne peuvent ˆetre inf´erieures `a 0.");
        }else{
            this . calories = calories ;
        }
    }
    public String getNom() {
        return this.nom;
    }

    public int getCalories() {
        return this.calories;
    }


}
