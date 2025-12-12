import package1.Cryptocurrency;
import package1.WalletException;

import java.util.ArrayList;
import java.util.Map;
import java.util.TreeMap;

public class Wallet {
    // numéro de la Wallet.
    private final int number;
    private String owner;
    // cash en argent.
    private int usd;
    private Map<Cryptocurrency,Double> wallet = new TreeMap<>();
    private ArrayList<Integer> devise_exchange_crypto = new ArrayList<Integer>();

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
        String format =
                "Wallet "+this.number+" de "+this.owner+".\n"+
                "Valeur indicative : "+ " USD\n"+
                '\t'+"Cash : "+ this.usd+" USD"+
                '\t'+"Crypto-monnaies : "+this.total_value_wallet()+" USD\n"+
                this.affiche_wallet_crypto();
        return format;
    }

    public int total_value_wallet() {
        int somme =0;
        for (Cryptocurrency currency : this.wallet.keySet()){
            somme += currency.getValue();
        }
        return somme;
    }

    public void affiche_wallet_crypto(){
        this.devise_exchange_crypto.add(43000);
        this.devise_exchange_crypto.add(2250);
        int i = 0;
        for(Cryptocurrency cryptocurrency: this.wallet.keySet()){
            System.out.println('\t'+'\t'+"- "+cryptocurrency.getName()+" à "+this.devise_exchange_crypto.get(i)+" USD = "+ cryptocurrency.getValue()*this.devise_exchange_crypto.get(i)+" USD");
            if(i< this.wallet.size()){i++;}
        }
    }
    public int buy (Cryptocurrency crypto, int quantite ) throws WalletException {
        if(this.getUsd() < crypto.getValue() * quantite){

        }else {
            throw new WalletException("You don't have enough money.");
        }
        return 0;
    }
    public int sell (Cryptocurrency crypto, int quantite ) throws WalletException{
        if(this.getUsd() < crypto.getValue() * quantite){

        }else {
            throw new WalletException("You don't have enough money.");
        }
        return 0;
    }
}
