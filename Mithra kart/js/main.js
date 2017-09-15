$('.carousel').carousel({
	interval:2000
});
$("#bannerItems :first-child").addClass("active");
// $("#categories :first-child").addClass("active");
$("#viewBtn").on('click',function(){
	var parent = $(this).parent();
	var image = parent.find("img").prop("src");
	var price = parent.find("p").val();
	console.log(parent);
	console.log(image);
	console.log(price);
});
// $("#quantity").on('change', function(){
// 	var max = parseInt($(this).attr('max'));
//           var min = parseInt($(this).attr('min'));
//           if ($(this).val() > max)
//           {
//               $(this).val(max);
//           }
//           else if ($(this).val() < min)
//           {
//               $(this).val(min);
//           }     
// 	var units = $(this).val();
// 	var unitPriceString = $("#productPrice").text();
// 	var unitPrice = parseInt(unitPriceString);
// 	var totalPrice = units * unitPrice;
// 	$("#totalPrice").text(totalPrice);
// 	console.log(totalPrice);
	  
// })

$('#export-btn').on('click', function(e){
        e.preventDefault();
        ResultsToTable();
    });
    
    function ResultsToTable(){    
        // $("#customerTable").table2excel({
        //     name: "Customer Details"
        // });
        // Excel 2000 html format 
 
        $('#customerTable').tableExport({type:'excel'});
    }

// function isNumber(evt) {
//     evt = (evt) ? evt : window.event;
//     var charCode = (evt.which) ? evt.which : evt.keyCode;
//     if (charCode > 31 && (charCode < 48 || charCode > 57)) {
//         return false;
//     }
//     return true;
// }
//  $(function () {
//        $( "#quantity" ).change(function() {
//           var max = parseInt($(this).attr('max'));
//           var min = parseInt($(this).attr('min'));
//           if ($(this).val() > max)
//           {
//               $(this).val(max);
//           }
//           else if ($(this).val() < min)
//           {
//               $(this).val(min);
//           }       
//         }); 
//     });