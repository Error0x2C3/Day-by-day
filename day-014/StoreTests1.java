package pratique;

import org.junit.Test;

import java.time.LocalDate;
import java.util.*;

import static org.junit.Assert.assertEquals;


public class StoreTests {

    private final Product newspaper =
            new Product("Le soir", 2.0,
                    LocalDate.of(2023, 12, 7));

    private final Product shaver =
            new Product("Rasoir jetable", 0.99,
                    LocalDate.of(2023, 12, 1));

    private final Television samsung =
            new Television("Samsung", 999.99,
                    LocalDate.of(2023, 9, 25),
                    21.0);

    private final Cookie cookies =
            new Cookie("Petits beurres", 4.99,
                    LocalDate.of(2023, 9, 25));

    private final Fruit appel =
            new Fruit("Pomme", 2.85,
                    LocalDate.of(2023, 12, 2));

    private final Meat lamb =
            new Meat("Cote Agneau", 17.85,
                    LocalDate.of(2023, 12, 5));

    @Test
    public void testDates() {
        assertEquals(LocalDate.of(2023, 12, 1), shaver.getSaleDate());
        assertEquals(LocalDate.of(2023, 12, 3), shaver.getSaleDate().plusDays(2));
        assertEquals(LocalDate.of(2024, 03, 1), shaver.getSaleDate().plusMonths(3));
        assertEquals(LocalDate.of(2024, 12, 1), shaver.getSaleDate().plusYears(1));
    }

    @Test
    public void testProducts() {
        Set<Product> products = new TreeSet<>();
        products.add(newspaper);
        products.add(shaver);
        // On remarque que les produits sont ordonnés par prix croissant
        assertEquals("[Rasoir jetable - prix : 0.99 - date mise en vente : 2023-12-01, " +
                        "Le soir - prix : 2.0 - date mise en vente : 2023-12-07]",
                products.toString());
    }

    @Test
    public void testDifferentKindProducts() {
        Set<Product> products = new TreeSet<>();
        products.add(newspaper);
        products.add(cookies);
        products.add(appel);
        products.add(shaver);
        products.add(samsung);
        products.add(lamb);

        // On met les produits dans une liste de String.
        // Chaque String contient que le nom et prix du produit (afin de simplifier l'affichage)
        List<String> nameAndPriceProducts = new ArrayList<>();
        for (Product p : products) {
            nameAndPriceProducts.add(p.getName() + " : " + p.getPrice());
        }
        // Le "toString" de cette liste doit être égale au String suivant:
        assertEquals("[Rasoir jetable : 0.99, Le soir : 2.0, Pomme : 2.85, " +
                        "Petits beurres : 4.99, Cote Agneau : 17.85, Samsung : 999.99]",
                nameAndPriceProducts.toString());
    }

    @Test
    public void testEatables() {
        List<Eatable> eatables = new ArrayList<>();

        eatables.add(cookies);
        eatables.add(appel);
        eatables.add(lamb);
        System.out.println(eatables);
        System.out.println("==================");
        // On veut trier cette liste selon les dates de péremption
        // eatables.sort(Eatable::compareTo);
        /*
        Déconseillé car
        Un product est déjà comparable,
        là je change la comparaison au niveau des Etables c'est pas bon.
        Peu avoir des confusions entre le comprable de Product et celui de l'interface.
         */
        eatables.sort(new Comparator<Eatable>() {
            @Override
            public int compare(Eatable o1, Eatable o2) {
                return o1.datePeremption().compareTo(o2.datePeremption());
            }
        }

        );
        System.out.println(eatables);
        // Les produits mangeables sont triés selon la date de péremption
        assertEquals("[Cote Agneau - prix : 17.85 - date mise en vente : 2023-12-05 - date de péremption : 2023-12-08, " +
                        "Pomme - prix : 2.85 - date mise en vente : 2023-12-02 - date de péremption : 2023-12-09, " +
                        "Petits beurres - prix : 4.99 - date mise en vente : 2023-09-25 - date de péremption : 2024-03-25]",
                eatables.toString());

        /*
        Comme les télévisions et les journaux ne sont pas mangeables, si on
        décommente les lignes suivantes, on ne veut pas que cela puisse compiler
         */
       /* eatables.add(samsung);
        eatables.add(newspaper);*/
    }

    @Test
    public void testExchangeables() {
        List<Exchangeable> exchangeables = new ArrayList<>();
        exchangeables.add(samsung);
        exchangeables.add(cookies);
        assertEquals("[Samsung - prix : 999.99 - date mise en vente : 2023-09-25 taille : 21.0, " +
                        "Petits beurres - prix : 4.99 - date mise en vente : 2023-09-25 - date de péremption : 2024-03-25]",
                exchangeables.toString());

        /*
        Comme les fruits, la viande et les journaux ne sont pas échangeables,
        si on décommente les lignes suivantes, on ne veut pas que cela puisse compiler
         */
//        exchangeables.add(appel);
//        exchangeables.add(lamb);
//        exchangeables.add(newspaper);
    }


}
