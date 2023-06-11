const moHideOrShow = (e)=>{
    
    const  name = e.target.name;
    const value = e.target.value;
    if(value === 'yes'){
        document.querySelectorAll(`[data-name=${name}]`).forEach((el)=>{
            el.style.display = ''
        })
        document.querySelectorAll(`[data-name2=${name}]`).forEach((el)=>{
            el.style.display = 'none'
        })
    }else{

        document.querySelectorAll(`[data-name=${name}]`).forEach((el)=>{
            el.style.display = 'none'
        })
        document.querySelectorAll(`[data-name2=${name}]`).forEach((el)=>{
            el.style.display = ''
        })

    }
}