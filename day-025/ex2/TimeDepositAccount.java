package labo8.ex2;

public class TimeDepositAccount extends SavingAccount{
    /*
     Compte d'épargne avec une promesse de laisser l'argent un
     certain nombre de mois avec une pénalité en cas de retrait anticipé.
     */
    // Nbr de mois à garder l'argent dans son compte en banque.
    private final int nbr_month;
    private final int month_initial; // le mois initial auquel commence le décompte.
    private int decompte_month;
    public TimeDepositAccount(double solde, double interet,int nbr_month,int month_initial){
        super(solde,interet);
        this.nbr_month = nbr_month;
        this.month_initial = month_initial;
        this.decompte_month = 0;
    }
    public int getNbr_month() {
        return this.nbr_month;
    }
    public int getMonth_initial(){
        return this.month_initial;
    }
    public int getDecompte_month() {
        return this.decompte_month;
    }
    public void setDecompte_month(int decompte_month) {
        this.decompte_month = decompte_month;
    }

    // Intérêt ajouté tous les mois.
    // Intérêt exprimé en pourcent.
    // + comptabiliser le nombre de mois.
    @Override
    public void addInterest(){
        if( this.getInteret() > 0){
            double bonus = (this.getSolde()/100) * this.getInteret();
            this.setSolde( this.getSolde() + bonus );
            this.setDecompte_month(this.getDecompte_month() +1);
        }
    }
    @Override
    // Retire un montant du compte et veille à ce que le compte reste en positif.
    // + vérifie si le retrait est prématuré.
    public boolean withdraw(double amount){
        if(this.getBalance() - amount > 0 && this.getDecompte_month() == this.nbr_month){
            this.setSolde(this.getSolde() - amount);
            return true;
        }else{
            this.get_penality();
            this.addInterest();
            return true;
        }
    }

    public void get_penality() {
        /*
         Pour un compte d'épargne avec engagement de durée (comme un Compte à Terme),
         la pénalité de retrait anticipé ne correspond pas à un montant que vous "payez" en plus,
         mais à une réduction des intérêts que vous auriez dû toucher.

         Au lieu de toucher le taux promis (ex: 3,5 %),
         la banque recalcule vos intérêts avec un taux dégradé
         pour la période où l'argent est resté sur
         le compte.
         La méthode : Capital initial * (taux initial - pénalité) * temps réel / 365
         Exemple :
          Vous placez 10 000 € à 3,5 % pour 12 mois.
          Vous retirez après 6 mois.
          La banque applique une pénalité de 1 % sur le taux.
          Le taux appliqué devient 2,5 % (3,5 % - 1 %).
          Vous recevez : (10 000 * 0.025 * 180)/365 = 123,28 euros
          (au lieu de 172,60 € sans pénalité).
         */
//        double interet_initial = this.getInteret();
//        double jour_ecoule = this.getDecompte_month() * 30.0;
//        double jour_total = this.getNbr_month() * 30.0;
//        double penalite = 1.0 / 100.0; // pénalité d'1 %.
//        return this.getSolde() * (interet_initial - penalite) * jour_ecoule / jour_total;
        this.setInteret( this.getInteret() - 1.0);
    }

}
