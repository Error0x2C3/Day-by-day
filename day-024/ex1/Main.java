package labo8.ex1;

public class Main {

    public static void main(String[] args) {
        PrimeFlixAccount primeFlixAccount = new PrimeFlixAccount("Bob");
        Movie newMovie = new Movie("Barbie", 2023, false, false);
        System.out.println(primeFlixAccount.getListMoviesRented());
        primeFlixAccount.rentMovie(newMovie);
        System.out.println(primeFlixAccount.getListMoviesRented());
        double bill = primeFlixAccount.getInvoice(); // devrait être égal à 4.99
        System.out.println(bill);
        System.out.println(primeFlixAccount.getListMoviesRented());

        StreamingQuality p = StreamingQuality.LOW;
        System.out.println(p.getPrice());
    }
}