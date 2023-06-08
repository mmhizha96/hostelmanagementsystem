import { TestBed } from '@angular/core/testing';

import { RoomallocationService } from './roomallocation.service';

describe('RoomallocationService', () => {
  let service: RoomallocationService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(RoomallocationService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
