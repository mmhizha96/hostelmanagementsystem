import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class RoomService {

  constructor(private http:HttpClient) {

   }
   reserveRoom(room:any,headers:any){
    return this.http.post('http://localhost:8000/api/resavations', room,headers);
   }
   unReserveRoom(room:any,headers:any){
    return this.http.post('http://localhost:8000/api/unreserve', room,headers);
   }
   findReservedRoom(data:any,headers:any){
    return this.http.post('http://localhost:8000/api/findresevedrooms',data, headers);
   }
  getRoomstoreserve(data:any,headers:any){
    return this.http.post('http://localhost:8000/api/getroomstoreserve',data, headers);
   }

   getReservedRoom(headers:any){
    return this.http.get('http://localhost:8000/api/resavations', headers);
   }
updateroom(data:any,headers:any)
{

  return this.http.post('http://localhost:8000/api/updateRoom', data,headers);
}

   getRooms(data:any,headers:any)
   {
    return this.http.post('http://localhost:8000/api/getrooms',data,headers);
   }
   deleteRoom(data:any,headers:any)
   {
    return this.http.post('http://localhost:8000/api/deleteRoom', data,headers);
   }
   createRooms(data:any,headers:any)
   {
return this.http.post('http://localhost:8000/api/addroom',data,headers);

   }
}
