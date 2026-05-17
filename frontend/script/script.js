const admin_btn = document.getElementById("admin-btn")
const agent_btn = document.getElementById("agent-btn")
const guichet_btn = document.getElementById("guichet-btn")


admin_btn.addEventListener('click', () => {
    window.location.href = "../frontend/admin-login.php"
})

agent_btn.addEventListener('click', () =>{
    window.location.href = "../frontend/agent.php"
})

guichet_btn.addEventListener('click', () =>{
    window.location.href = "../frontend/guichet-login.php"
})

/*const cat1 = document.getElementById('Cat_1')
const cat2 = document.getElementById('Cat_2')
const cat3 = document.getElementById('Cat_3')
const cat4 = document.getElementById('Cat_4')
*/
/*select.addEventListener('click', () =>{
    cat1.addEventListener('click', () =>{
        amount.innerHTML = "500"
    })
    cat2.addEventListener('click', () =>{
        amount.innerHTML = "1000"
    })
    cat3.addEventListener('click', () =>{
        amount.innerHTML = "1500"
    })
    cat1.addEventListener('click', () =>{
        amount.innerHTML = "3000"
    })
})
*/