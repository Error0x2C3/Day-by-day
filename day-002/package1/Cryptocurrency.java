package package1;

public class Cryptocurrency {
    final String name;
    int value;

    public Cryptocurrency(String name, int value) throws RuntimeException {
        if(name != null){
            this.name = name;
        }else{
            throw new RuntimeException("Name must not be null !");
        }
        this.value = value;
    }

    public String getName() {
        return name;
    }
    public int getValue() {
        return value;
    }

    public void setValue(int value) {
        this.value = value;
    }


    @Override
    public boolean equals(Object o){
        if(o instanceof Cryptocurrency){
            Cryptocurrency crypto = (Cryptocurrency) o;
            return this.name.equals(crypto.name);
        }
        return false;
    }
}
