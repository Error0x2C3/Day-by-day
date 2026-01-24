package labo8.ex1;

import java.util.ArrayList;

public class StreamingPrimeFlixAccount extends PrimeFlix {
    private final StreamingQuality quality;
    private final ArrayList<Movie> ListMoviesStream = new ArrayList<>();
    public StreamingPrimeFlixAccount(String userName, StreamingQuality quality){
        super(userName);
        this.quality = quality;
    }
    public StreamingQuality get_Quality(){
        return this.quality;
    }
    public ArrayList<Movie> get_ListMoviesStream(){
        return this.ListMoviesStream;
    }


    /*
    Permet de visionner un film en streaming, après avoir vérifié que le nombre
    de films visionnés en streaming dans le mois courant ne dépasse pas 200;
     */
    public void streamMovie(Movie movie) {
        if(this.ListMoviesStream.size() < 200){
            // Deuxième manière de faire en calculant le prix dès l'ajout dans le tableau.
            if(this.get_Quality() == StreamingQuality.HIGH){
                this.setBill(15.99);
            }else{
                this.setBill(10.99);
            }
            this.ListMoviesStream.add(movie);
        }
    }

    @Override
    public void printHistory() {
        /*
        Affiche l'historique des films loués et streamés dans le mois courant ainsi que le nom d'utilisateur.
         */
        System.out.println("Le compte de "+this.getUserame());
        System.out.println("Liste des films loués durant le mois :");
        System.out.println("==============================");
        System.out.println();
        for (int i = 0; i<this.getListMoviesRented().size(); i++){

            System.out.println(this.getListMoviesRented().get(i));
            System.out.println();
        }
        System.out.println("****************************************");
        System.out.println();
        System.out.println("Liste des films streamés durant le mois :");
        System.out.println("==============================");
        System.out.println();
        for (int i = 0; i<this.get_ListMoviesStream().size(); i++){

            System.out.println(this.get_ListMoviesStream().get(i));
            System.out.println();
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
         */
        // Deuxième Manière de faire en reparcourant tout le tableau.
//        Double bill = 0.00;
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
//                if(this.get_Quality() == StreamingQuality.HIGH){
//                    bill +=15.99;
//                }else{
//                    bill +=10.99;
//                }
//            }
//        }
//        this.get_ListMoviesStream().clear();
        this.getListMoviesRented().clear();
        this.get_ListMoviesStream().clear();
        return Math.round(this.getBill() * 100.0) / 100.0;
    }
}
