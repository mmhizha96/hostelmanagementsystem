import { Hostel } from './../../../../models/hostel.model';
import { Component, OnDestroy, OnInit } from '@angular/core';
import { ToastrService } from 'ngx-toastr';
import { Subject } from 'rxjs';
import { AuthService } from 'src/app/services/auth.service';
import { HostelService } from 'src/app/services/hostel.service';
import Swal from 'sweetalert2'


@Component({
  selector: 'app-hostels',
  templateUrl: './hostels.component.html',
  styleUrls: ['./hostels.component.css']
})
export class HostelsComponent implements OnInit,OnDestroy {
  dtoptions: DataTables.Settings = {};
  dtTrigger:Subject<any>=new Subject<any>();
  hostels:any ;
  hostel= new Hostel();
  toastr: any;
  constructor( private hostelservice: HostelService, private toast : ToastrService,private authservice:AuthService){

  }
  ngOnInit(): void {
    this.dtoptions = {
      pagingType: 'full_numbers',
      searching:true,

    lengthChange:false,
    language:{
      searchPlaceholder:'Text Customer'
    }

    };
    this.getHostels();


  }
  updateHostel(){

console.log(this.hostel.hostel_name);
  this.hostelservice.updateHostel(this.hostel,{ headers: this.authservice.getHeaders() }).subscribe(res=>{
    if(JSON.parse(JSON.stringify(res)).hasOwnProperty('message'))
    {

      Swal.fire({
        position: 'top-end',
        icon: 'success',
        width: 200,
        title: 'Saved',
        customClass:'swalheight',
        showConfirmButton: false,
        timer: 1500
      })

    }
    else{
      Swal.fire({
        position: 'top-end',
        customClass:'swalheight',

        title: 'Error!',
        text: 'thereb was an error',
        icon: 'error'

      })

    }

  })


  }
  setHostel_id(hostel_id:any){
this.hostel.hostel_id=hostel_id;
console.log(this.hostel.hostel_id);
  }
deleteHostel(hostel_id:any)
{

  this.hostelservice.deleteHostel({'hostel_id':hostel_id},{ headers: this.authservice.getHeaders() }).subscribe(res=>{
    if(JSON.parse(JSON.stringify(res)).hasOwnProperty('message'))
    {
      alert('deleted')
    }
    else{
      alert("could not delete")

    }
  })
}

  getHostels()
  {
    this.hostelservice.getHostels(1).subscribe(res=>{
      console.log(res);
      this.hostels=res;
      this.dtTrigger.next(null);

    })
  }

  ngOnDestroy(): void {
    this.dtTrigger.unsubscribe();
  }


}
