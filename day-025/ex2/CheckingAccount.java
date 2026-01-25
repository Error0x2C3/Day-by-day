package labo8.ex2;

public class CheckingAccount extends BankAccount{
    /*
        Compte courant sans intérêt mais avec un nombre de transactions mensuelles gratuites.
        Les transactions suivantes sont payantes et déduites du compte.
         */
    // Nbr de transactions gratuites.
    private int nbr_free_transactions;
    private int nbr_transactions;
    public CheckingAccount(double solde, int nbr_free_transactions){
        super(solde);
        this.nbr_free_transactions = nbr_free_transactions;
    }
    public int getNbr_free_transactions() {
        return nbr_free_transactions;
    }
    public void setNbr_free_transactions(int nbr_free_transactions) {
        this.nbr_free_transactions = nbr_free_transactions;
    }
    public int getNbr_transactions() {
        return nbr_transactions;
    }
    public void setNbr_transactions(int nbr_transactions) {
        this.nbr_transactions = nbr_transactions;
    }


    @Override
    // Ajoute un montant au compte après avoir vérié que ce montant est positif
    // + comptabiliser le nbr de dépôt pour qu'en de mois l'on puisse compter les frais.
    public boolean deposit(double amount){
        if(amount >0.0){
            this.setSolde(this.getSolde()+ amount);
            if(this.nbr_free_transactions > 0){
                this.nbr_free_transactions -= 1;
            }else{
                this.nbr_transactions +=1;
            }
            return true;
        }
        return false;
    }

    @Override
    // Retire un montant du compte et veille à ce que le compte reste en positif.
    // + comptabiliser le nbr de retrait pour qu'en de mois l'on puisse compter les frais.
    public boolean withdraw(double amount){
        if(this.getBalance() - amount > 0){
            this.setSolde(this.getSolde() - amount);
            if(this.nbr_free_transactions > 0){
                this.nbr_free_transactions -= 1;
            }else{
                this.nbr_transactions +=1;
            }
            return true;
        }
        return false;
    }

    // Déduit les frais du compte chaque fin de mois.
    public void deductFees(int frais){
        this.setSolde( this.getSolde() - (frais * this.getNbr_transactions()) );
    }
}
