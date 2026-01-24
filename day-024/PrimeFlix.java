package labo8.ex1;

import java.util.ArrayList;

public abstract class PrimeFlix {
    /*
    Représente un compte utilisateur donnant accès à la location de film (VOD).
    */
    String userame;
    // Liste des films loués.
    final ArrayList<Movie> listMoviesRented = new ArrayList<>();
    Double bill = 0.0;
    public PrimeFlix(String userName){
        this.userame = userName;
    }

    public Double getBill(){
        return this.bill;
    }

    public void setBill(Double bill) {
        this.bill += bill;
    }

    public String getUserame() {
        return userame;
    }
    public void setUserame(String userame) {
        this.userame = userame;
    }

    public ArrayList<Movie> getListMoviesRented(){
        return this.listMoviesRented;
    }

    /*
    Loue un film. Les films datant de plus de 5 ans sont considérés
    comme anciens.
    */
    public void rentMovie(Movie movie){
        // Première manière de faire en calculant le prix dès l'ajout dans le tableau.
        if (movie.isOld()) {
            this.setBill(2.99);
        } else {
            this.setBill(4.99);
        }
        this.getListMoviesRented().add(movie);
    }

    /*
    Affiche l'historique des films loués dans le mois courant ainsi que le nom d'utilisateur.
    */
    public abstract void  printHistory();
    /*
    Retourne le montant à payer à la fin du mois en fonction du nombre de films loués (récents
    et anciens). Ceci efface également l'historique;
    La facturation se fait en fin de mois en fonction du nombre de films loués avec la tarification suivante :
        Film récent : 4.99 euros.
        Film ancien : 2.99 euros.
    */
    public abstract Double getInvoice();


}
