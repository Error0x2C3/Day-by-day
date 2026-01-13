package Ex1;

import java.util.HashMap;
import java.util.Map;

public class Plat {

    private final String nom ;
    private Map<Ingredient, Integer > ingredients = new HashMap< >();

    Plat(String nom){
        this.nom = nom;
    }
    public String getNom() {
        return this.nom;
    }

    public Map<Ingredient, Integer> getIngredients() {
        return this.ingredients;
    }
}
