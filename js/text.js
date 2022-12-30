const form = document.querySelector(".typing-area"),
incoming_id = form.querySelector(".incoming_id").value,
inputField = form.querySelector(".input-field"),
sendBtn = form.querySelector("button"),
chatBox = document.querySelector(".chat-box"),
scroll_to = document.querySelector("html, body");

form.onsubmit = (e)=>{
    e.preventDefault();
}

inputField.onclick = ()=>{
    scrollToBottom();
}


inputField.onkeyup = ()=>{
    scrollToBottom();
    if(inputField.value != ""){
        sendBtn.classList.add("active");
    }else{
        sendBtn.classList.remove("active");
    }
}

sendBtn.onclick = ()=>{
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "/qwitter/php/insert-text.php", true);
    xhr.onload = ()=>{
      if(xhr.readyState === XMLHttpRequest.DONE){
          if(xhr.status === 200){
              inputField.value = "";
            //   scrollToBottom();
          }
      }
    }
    let formData = new FormData(form);

    if(lock == 1){
        let msg = enc(formData.get("message"));
        formData.set('message', msg);
    }
    xhr.send(formData);
    scrollToBottom();
}
chatBox.onmouseenter = ()=>{
    chatBox.classList.add("active");
}

chatBox.onmouseleave = ()=>{
    chatBox.classList.remove("active");
}

setInterval(() =>{
    // scrollToBottom();
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "../php/get-text.php", true);
    xhr.onload = ()=>{
      if(xhr.readyState === XMLHttpRequest.DONE){
          if(xhr.status === 200){
            let data = xhr.response;
            // alert(data);
            chatBox.innerHTML = data;
            if(!chatBox.classList.contains("active")){
              }
          }
      }
    }
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("incoming_id="+incoming_id);
}, 500);

function scrollToBottom(){
    scroll_to.scrollTop = chatBox.scrollHeight;
  }

const chars = "=abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"
const emoji = ["🔑", "⚡️" , "👍" ,"🦔","🐀","😮","🤭","🌵","😢","👥","👤","🗣","😎","😛","👣","👀","😉","🙂","👹","🤸","👾","👽","😍","🌻","😈","🍄","😀","🕸","🦇","🐌","🕷","🐑","🤚🏼","🐅","🦓","🐡","🐴","🐜","👋🏼","🐞","🤷🏻","👅","🧑🏻","🧝🏼","💑🏾","🤶🏼","👃🏼","🤲🏼","🏴","👐🏼","🙌🏼","🖐🏼","🏳️","🔭","⚱️","💣","⏱","📱","🏯","🎯","🎬","🎨","✒️" ]
const arrChar = chars.split("")

function enc(text){
            text = btoa(text)
            text =  rs(text);
             for(j=0 ; j<text.length; j++){
            for(i = 0; i<chars.length; i++){
            text = text.replace(arrChar[i], emoji[i]);
            }
        }
        return text;
        }

function rs(str) {
    var splitString = str.split(""); 
    var reverseArray = splitString.reverse(); 
    var joinArray = reverseArray.join(""); 
    return joinArray;
}

setInterval(function(){
    // scrollToBottom();
},1)