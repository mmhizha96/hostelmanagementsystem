import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class LocationService {

  constructor(private http:HttpClient) { }

  getlocations(headers:any)
  {
    return this.http.get('http://localhost:8000/api/getlocations',headers);
  }


}
