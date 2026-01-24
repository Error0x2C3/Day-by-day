package labo8.ex1;

public class PremiumPrimeFlixAccount extends StreamingPrimeFlixAccount{
    /*
        Ce compte donne droit à toutes les
        fonctionnalités d'un compte StreamingPrimeFlixAccount
        en qualité StreamingQuality.HIGH, avec en plus 2 films
        en avant-première gratuits.
        Le prix est égal au prix de l'abonnement Streaming (high quality) auquel s'ajoute un
        supplément de 4 euros.
         */
    private int free_film_previous = 2;
    public PremiumPrimeFlixAccount(String userName){
        super(userName,StreamingQuality.HIGH);
    }
    public int getFree_film_previous() {
        return this.free_film_previous;
    }

    public void setFree_film_previous(int compte) {
        this.free_film_previous -= compte;
    }
    /*
    Loue un film. Les films datant de plus de 5 ans sont considérés
    comme anciens.
    */
    public void rentMovie(Movie movie){
        // Première manière de faire en calculant le prix dès l'ajout dans le tableau.
        if(movie.isPremiere() && this.getFree_film_previous() > 0){
            this.setFree_film_previous(1);
        }else{
            if (movie.isOld()) {
                this.setBill(2.99);
            } else {
                this.setBill(4.99);
            }
        }
        this.getListMoviesRented().add(movie);
    }
    /*
    Permet de visionner un film en streaming, après avoir vérifié que le nombre
    de films visionnés en streaming dans le mois courant ne dépasse pas 200;
    */
    @Override
    public void streamMovie(Movie movie) {
        if(this.get_ListMoviesStream().size() < 200){
            // Deuxième manière de faire en calculant le prix dès l'ajout dans le tableau.
            /*
            StreamingPrimeFlixAccount
            a la qualité StreamingQuality.HIGH par défaut 15.99 + 4 euros.
            ET
            Il a droit à deux avant première gratuite.
            */
            if(movie.isPremiere() && this.getFree_film_previous() > 0){
                this.setFree_film_previous(1);
            }else{
                this.setBill(15.99 + 4);
            }
            this.get_ListMoviesStream().add(movie);
        }
    }

    @Override
    public Double getInvoice() {
        /*
        Retourne le montant à payer à la fin du mois en fonction du nombre de films loués (récents
        et anciens). Ceci efface également l'historique;
        La facturation se fait en fin de mois en fonction du nombre de films loués avec la tarification suivante :
            Film récent : 4.99 euros.
            Film ancien : 2.99 euros.
        + le nombre de films streamés :
            Low qualité : 10.99 euros.
            High qualité: 15.99 euros.
        À droit à deux films en avant-première gratuits.
         */
//
//        Double bill = 0.00;
//        int free_movie = 2;
//        if(!this.getListMoviesRented().isEmpty()){
//            for(int i = 0; i<this.getListMoviesRented().size(); i++){
//                if(!this.getListMoviesRented().get(i).isOld()){
//                    bill +=4.99F;
//                }else{
//                    bill +=2.99F;
//                }
//            }
//        }
//        this.getListMoviesRented().clear();
//        if(!this.get_ListMoviesStream().isEmpty()){
//            for(int i = 0; i<this.get_ListMoviesStream().size();i++){
//                /*
//                StreamingPrimeFlixAccount
//                a la qualité StreamingQuality.HIGH par défaut.
//                 */
//                bill +=15.99 + 4;
//            }
//        }
//        this.get_ListMoviesStream().clear();
        this.getListMoviesRented().clear();
        this.get_ListMoviesStream().clear();
        return Math.round(this.getBill() * 100.0) / 100.0;
    }
}
