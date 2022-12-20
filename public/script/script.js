function switchLeftmenu(action){
    let leftmenu = document.getElementById("leftmenu");
    let allbijustify = document.querySelectorAll("#mainnav .btn-leftmenu .bi-justify");
    let allbix = document.querySelectorAll("#mainnav .btn-leftmenu .bi-x");
    if(action == 0){
        leftmenu.style.display = "none";
        allbijustify.forEach(node=>{
            node.style.display = "block";
        })
        allbix.forEach(node=>{
            node.style.display = "none";
        })
    }
    if(action == 1){
        leftmenu.style.display = "flex";
        allbijustify.forEach(node=>{
            node.style.display = "none";
        })
        allbix.forEach(node=>{
            node.style.display = "block";
        })
    }
}

function wait(seconds){
    return new Promise((resolve)=>{
        setTimeout(()=>{
            resolve(1);
        },seconds * 1000);
    });
}

function clone(data){
    if(data != null){
        return JSON.parse(JSON.stringify(data));
    }
    return data;
}


function openSnackbar(data,seconds=1){
    let div = document.createElement("div");
    div.classList.add("snackbar");
    div.textContent = data;
    document.querySelector("body").appendChild(div);
    window.setTimeout((e)=>{
        div.remove();
    }, seconds * 1000);
}

function readFile(file){
    return new Promise((resolve,err) => {
      let reader = new FileReader();
      reader.onload = (e) => {
        resolve(e.target.result);
      };
      reader.readAsDataURL(file);
    });
  }

//fetch
async function fetchGet(url){
    try {
        let response = await fetch(url,{
            headers: {'X-Requested-With': 'XMLHttpRequest'},
            method: 'get'
        });
        return response = await response.json();
    } catch (err) {
        throw new Error(err);
    }
}

async function fetchPost(url,data){
    try {
        let response;
        if(data instanceof FormData){
            response = await fetch(url,{
                headers: {'X-Requested-With': 'XMLHttpRequest'},
                method: 'post',
                body: data
            });
        }else{
            response = await fetch(url,{
                headers: {'X-Requested-With': 'XMLHttpRequest'},
                method: 'post',
                body: JSON.stringify(data)
            });
        }
        return response = await response.json();
    } catch (err) {
        throw new Error(err);
    }
}