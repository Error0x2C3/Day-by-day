package labo8.ex1;

public class PrimeFlixAccount extends PrimeFlix{
    /*
    Représente un compte utilisateur donnant accès à la location de film (VOD).
    */
    public PrimeFlixAccount(String userName){
        super(userName);
    }


    @Override
    public void printHistory(){
        /*
        Affiche l'historique des films loués dans le mois courant ainsi que le nom d'utilisateur.
         */
        System.out.println("Le compte de "+this.getUserame());
        System.out.println("Liste des films loués :");
        System.out.println("==============================");
        System.out.println();
        for (int i = 0; i<this.getListMoviesRented().size(); i++){

            System.out.println(this.getListMoviesRented().get(i));
            System.out.println();
        }
    }
    @Override
    public Double getInvoice(){
        /*
        Retourne le montant à payer à la fin du mois en fonction du nombre de films loués (récents
        et anciens). Ceci efface également l'historique;
        La facturation se fait en fin de mois en fonction du nombre de films loués avec la tarification suivante :
            Film récent : 4.99 euros.
            Film ancien : 2.99 euros.
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
        this.getListMoviesRented().clear();
        return Math.round(this.getBill() * 100.0) / 100.0;
    }
}

