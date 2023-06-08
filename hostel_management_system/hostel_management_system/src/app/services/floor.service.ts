import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class FloorService {

  constructor(private http:HttpClient) { }
  deleteFloor(data:any,headers:any)
  {
    return this.http.post('http://localhost:8000/api/deletefloor',data,headers);
  }

  getBatchFloor(data:any,headers:any)
  {
    return this.http.post('http://localhost:8000/api/getbatchfloors',data,headers);
  }
  getFloor(data:any,headers:any)
  {
    return this.http.post('http://localhost:8000/api/getfloors',data,headers);
  }
  addfloors(data:any,headers:any){
    return this.http.post('http://localhost:8000/api/addfloor',data,headers);
  }
}
