import { TestBed } from '@angular/core/testing';

import { RoomtypeService } from './roomtype.service';

describe('RoomtypeService', () => {
  let service: RoomtypeService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(RoomtypeService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
