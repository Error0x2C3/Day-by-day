public class Wallet {
    // numéro de la Wallet.
    private final int number;
    private String owner;
    // cash en argent.
    private int usd;

    public Wallet(String owner, int number, int usd){
        this.owner = owner;
        this.number = number;
        this.usd = usd;
    }

    public int getNumber() {
        return number;
    }
    public String getOwner() {
        return owner;
    }
    public int getUsd() {
        return usd;
    }

    public void setOwner(String owner) {
        this.owner = owner;
    }
    public void setUsd(int usd) {
        this.usd = usd > 0 ? usd : this.usd;
    }

    @Override
    public String toString(){
        return String.format(
                "Wallet "+this.number+" de "+this.owner+" ,"+
                "Valeur indicative :"+" USD"+
                " Cash :"+ "USD"
        );
    }
}
