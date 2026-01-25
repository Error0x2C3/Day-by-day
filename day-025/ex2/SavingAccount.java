package labo8.ex2;

public class SavingAccount extends BankAccount{
    // Compte qui offre un intérêt au bout de chaque mois.
    private double interet;
    public SavingAccount(double solde,double interet){
        super(solde);
        this.interet = interet;
    }

    public double getInteret() {
        return this.interet;
    }
    public void setInteret(double interet) {
        this.interet = interet;
    }
    // Intérêt ajouté tous les mois.
    // Intérêt exprimé en pourcent.
    public void addInterest(){
        if( this.getInteret() > 0){
            double bonus = (this.getSolde()/100) * this.getInteret();
            this.setSolde( this.getSolde() + bonus );
        }

    }
}
