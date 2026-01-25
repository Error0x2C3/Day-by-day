package labo8.ex2;

public class BankAccount {
    // BankAccount est un compte simple.
    private double solde;
    public BankAccount(double solde){
        this.solde = solde;
    }
    public double getSolde() {
        return this.solde;
    }
    public void setSolde(double solde) {
        this.solde = solde;
    }
    // renvoie le solde du compte
    public double getBalance(){
        return this.getSolde();
    }
    // Ajoute un montant au compte après avoir vérié que ce montant est positif
    public boolean deposit(double amount){
        if(amount >0.0){
            this.setSolde(this.getSolde()+ amount);
            return true;
        }
        return false;
    }

    // Retire un montant du compte et veille à ce que le compte reste en positif.
    public boolean withdraw(double amount){
        if(this.getBalance() - amount > 0){
            this.setSolde(this.getSolde() - amount);
            return true;
        }
        return false;
    }
}
