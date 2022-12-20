class Category{
    id = null;
    status = 1;
    title = "";

    constructor() {
        
    }

    set(data){
        this.id = data["id"];
        this.status = data["status"];
        this.title = data["title"];
    }
}