import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class LevelsService {

  constructor(private http:HttpClient) { }

  getlevels(headers:any){
    return this.http.get('http://localhost:8000/api/getacademiclevel',headers);
  }
}
