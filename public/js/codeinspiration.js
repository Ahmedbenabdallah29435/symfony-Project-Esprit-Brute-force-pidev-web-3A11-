
function addWishlist(){
    console.log("Adding item wishlist");
}

function removeWishlist(){
    console.log("remove item wishlist");
}

var wishlistButton = document.querySelector('.wishlist-btn');

wishlistButton.addEventListener('click',function(){
     
    if (this.classList.contains('active')){
        removeWishlist();
        this.classList.remove('active');

     }else {
          this.classList.add('active');
     addWishlist();

     }
    
   
})